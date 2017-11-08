<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\BrandForm;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
class BrandController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    //>>1显示品牌列表
    public function actionIndex()
    {
        //实例化分页组件
        $pager = new Pagination();
        //查询数据
        $brand = Brand::find();
        //每页显示条数据
        $pager->pageSize = 3;
        //总记录数据
        $pager->totalCount = $brand->where(['status'=>['1','0']])->count();
        //设置偏移量
        $rows = $brand->where(['status'=>['1','0']])->limit($pager->limit)->offset($pager->offset)->all();
        //分配数据
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }

    //>>2.添加品牌
    public function actionAdd()
    {
        //显示添加表单
        $model = new BrandForm();
        $request = new Request();
        if($request->isPost)
        {
            $brand = new Brand();
            //接收表单数据
            $model->load($request->post());
            //var_dump($model);die;
            //将文件封装成对象
            //$model->imgFile = UploadedFile::getInstance($model,'imgFile');

            //验证表单数据
            if ($model->validate())
            {
               /* if($model->imgFile!=null)
                {
                    //获取文件扩展名
                    $ext = $model->imgFile->extension;
                    //拼接文件路径
                    $fileName = '/upload/'.uniqid().'.'.$ext;
                    //指定文件保存的路径
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,0);
                    $brand->logo = $fileName;
                }else{
                    $brand->logo =  \Yii::getAlias('@web').'/upload/2.jpg';
                }*/

                //将文件路径保存到数据库
                $brand->name = $model->name;
                $brand->intro = $model->intro;
                $brand->status = $model->status;
                $brand->logo = $model->logo;
                $brand->save(false);
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect('index');
            }
        }
        //跳转
        return $this->render('add',['model'=>$model]);
    }

    //>>3.修改品牌
    public function actionEdit()
    {
        $model = new BrandForm();
        //接收id
        $request = new Request();
        $id = $request->get('id');
        //根据id查询数据
        $row = Brand::findOne($id);
        $model->name = $row->name;
        $model->intro = $row->intro;
        $model->logo = $row->logo;
        $model->status = $row->status;

        if($request->isPost)
        {
            //接收修改后的表单数据
            $model->load($request->post());
            //将文件封装成对象
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            //验证数据
            if($model->validate())
            {   /*//判断是否有图片上传
                if ($model->imgFile!=null)
                {
                    //获取文件扩展名
                    $ext = $model->imgFile->extension;
                    //拼接文件路径
                    $fileName = '/upload/'.uniqid().'.'.$ext;
                    //指定文件保存的路径
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,0);
                    $row->logo = $fileName;
                }*/
                //将文件路径保存到数据库
                $row->name = $model->name;
                $row->intro = $model->intro;
                $row->status = $model->status;
                $row->logo = $model->logo;
                $row->save();
                //跳转
                \Yii::$app->session->setFlash('success','修改成功');
                $this->redirect('index');
            }

        }

        //将数据回显到表单
        return $this->render('edit',['model'=>$model]);

    }

    //>>4.删除品牌
    public function actionDel()
    {
        //接收id
        $request = new Request();
        $id = $request->post('id');
        //根据id查询数据
        $brand = Brand::findOne($id);
        //删除数据
        $brand->status = -1;
        $result = $brand->save(false);
        //响应ajax
        if($result)
        {
          echo '1';
        }

    }

    //ajax文件上传
    public function actionUpload()
    {
        //接收上传文件
        $request = new Request();
        if($request->isPost)
        {
            $imgFile = UploadedFile::getInstanceByName('file');
            //判断是否有文件上传
            if($imgFile)
            {
                //获取文件扩展名
                $ext = $imgFile->extension;
                //拼接文件路径
                $fileName = '/upload/'.uniqid().'.'.$ext;
                //指定文件保存的路径
                $imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,0);
                //--------------------------------------------------------
                //将文件上传到七牛云
                // 需要填写你的 Access Key 和 Secret Key
                $accessKey ="uWk6rf6cKKp40JXcQTBxoT4t9ngyBQl9oLcPXfLb";
                $secretKey = "gOkkcbu3BmPXAR0Ob6TzyVYaUZ9JKEZ6GWI1fSvv";
                //对象空间的名称
                $bucket = "itshop";

                // 构建鉴权对象
                $auth = new Auth($accessKey, $secretKey);

                // 生成上传 Token
                $token = $auth->uploadToken($bucket);

                // 要上传文件的本地路径
                $filePath = \Yii::getAlias('@webroot').$fileName;

                // 上传到七牛后保存的文件名
                $key = $fileName;

                // 初始化 UploadManager 对象并进行文件的上传。
                $uploadMgr = new UploadManager();

                // 调用 UploadManager 的 putFile 方法进行文件的上传。
                list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
               // echo "\n====> putFile result: \n";
                if ($err !== null) {
                    //上传失败返回错误信息
                    return json_encode(['url'=>$err]);
                } else {
                    //上传成功返回七牛云路径
                    return json_encode(['url'=>'http://oyy23zq8f.bkt.clouddn.com/'.$fileName]);
                }

                //--------------------------------------------------------

            }

        }


    }
    //回收列表
    public function actionRecycle()
    {
        //实例化分页组件
        $pager = new Pagination();
        //查询数据
        $brand = Brand::find();
        //每页显示条数据
        $pager->pageSize = 3;
        //总记录数据
        $pager->totalCount = $brand->where(['status'=>['-1']])->count();
        //设置偏移量
        $rows = $brand->where(['status'=>['-1']])->limit($pager->limit)->offset($pager->offset)->all();
        //分配数据
        return $this->render('recycle',['rows'=>$rows,'pager'=>$pager]);
    }

    //恢复
    public function actionRestore()
    {
        //接收id
        $request = new Request();
        $id = $request->post('id');
        //根据id查询数据
        $row = Brand::findOne($id);
        //恢复数据
        $row->status = 1;
        $result = $row->save(false);

        //响应ajax
        if($result)
        {
            echo '1';
        }
    }

    //永久删除
    public function actionDelete()
    {
        //接收id
        $request = new Request();
        $id = $request->post('id');
        //根据id查询数据
        $row = Brand::findOne($id);
        //删除数据
        $result = $row->delete();
        //响应ajax
        if($result)
        {
            echo '1';
        }
    }


   /* //测试七牛云
    public function actionTest()
    {
        // 需要填写你的 Access Key 和 Secret Key
        $accessKey ="uWk6rf6cKKp40JXcQTBxoT4t9ngyBQl9oLcPXfLb";
        $secretKey = "gOkkcbu3BmPXAR0Ob6TzyVYaUZ9JKEZ6GWI1fSvv";
        //对象空间的名称
        $bucket = "itshop";

        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);

        // 生成上传 Token
        $token = $auth->uploadToken($bucket);

        // 要上传文件的本地路径
        $filePath = \Yii::getAlias('@webroot').'/upload/2.jpg';

        // 上传到七牛后保存的文件名
        $key = '/upload/2.jpg';

        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        echo "\n====> putFile result: \n";
        if ($err !== null) {
            var_dump($err);
        } else {
            var_dump($ret);
        }

    }*/

}