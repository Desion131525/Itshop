<?php
namespace backend\controllers;
use backend\filters\AdminFilter;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\Goods_category;
use backend\models\Goods_categoryForm;
use backend\models\Goods_day_count;
use backend\models\goods_gallery;
use backend\models\Goods_galleryForm;
use backend\models\Goods_intro;
use backend\models\Goods_introForm;
use backend\models\GoodsForm;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 20:25
 */
class GoodsController extends Controller
{
    public $enableCsrfValidation = false;
    //>>1显示商品列表
    public function actionIndex()
    {
        $model = new GoodsForm();
        //实例化分页组件
        $pager = new Pagination();
        //查询数据
        $goods = Goods::find();
        //每页显示条数据
        $pager->pageSize = 5;
        //总记录数据
        $pager->totalCount = $goods->where(['status'=>1])->count();
        //设置偏移量
        $rows = $goods->where(['status'=>[0,1]])->limit($pager->limit)->offset($pager->offset)->all();
        //分配数据
        //var_dump($rows);die;
        return $this->render('index',['rows'=>$rows,'pager'=>$pager,'model'=>$model]);
    }

    //>>2.添加商品
    public function actionAdd()
    {
        //显示添加表单
        $model = new GoodsForm();
        $request = new Request();

        //创建商品详情表单模型
        $goods_introForm = new Goods_introForm();

        //获取品牌分类
        $brand = Brand::find()->select(['id','name'])->where(['status'=>1])->all();
        $brand = ArrayHelper::map($brand,'id','name');

        //获取商品分类数据
        $gc = Goods_category::find()->all();
        $goods_categoryForm = new Goods_categoryForm();
        $goods_categoryForm->parent_id = 0;
        $gc = ArrayHelper::map($gc,'id','name');

       /* //查询当天商品添加次数
        $day = date('Y-m-d',time());
        $goods_day = Goods_day_count::find()->where(['day'=>$day])->one();

        if($goods_day!=null)
        {
            //有次数则拼接货号 = 当天日期+当天添加的商品次数
            $count = str_pad($goods_day->count+1,'5','0',STR_PAD_LEFT);
            $sn = date('Ymd',time()).$count;
            $model->sn = $sn;
            //var_dump($sn);die;

        }else{
            //没有则货号 = 当天日期+00001
            $count = str_pad(1,'5','0',STR_PAD_LEFT);
            $sn = date('Ymd',time()).$count;
            $model->sn = $sn;
            //var_dump($sn);die;
        }*/

        if($request->isPost)
        { //查询当天商品添加次数
            $day = date('Y-m-d',time());
            $goods_day = Goods_day_count::find()->where(['day'=>$day])->one();

            if($goods_day!=null)
            {
                //有次数则拼接货号 = 当天日期+当天添加的商品次数
                $count = str_pad($goods_day->count+1,'5','0',STR_PAD_LEFT);
                $sn = date('Ymd',time()).$count;
                $model->sn = $sn;
                //var_dump($sn);die;

            }else{
                //没有则货号 = 当天日期+00001
                $count = str_pad(1,'5','0',STR_PAD_LEFT);
                $sn = date('Ymd',time()).$count;
                $model->sn = $sn;
                //var_dump($sn);die;
            }

            $goods = new Goods();
            $goods_content = new Goods_intro();

            //接收表单数据
            $model->load($request->post());
            $goods_introForm->load($request->post());
            $goods_categoryForm->load($request->post());
            //var_dump($model);
            //var_dump($goods_introForm);die;
            //将文件封装成对象
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');

            //验证表单数据
            if ($model->validate()&&$goods_introForm->validate())
            {
                if($model->imgFile!=null)
                {
                    //获取文件扩展名
                    $ext = $model->imgFile->extension;
                    //拼接文件路径
                    $fileName = '/upload/'.uniqid().'.'.$ext;
                    //指定文件保存的路径
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,0);
                    $goods->logo = $fileName;
                }else{
                    $goods->logo =  \Yii::getAlias('@web').'/upload/2.jpg';
                }

                //将文件路径保存到数据库
                $goods->name = $model->name;
                $goods->sn = $model->sn;
                $goods->brand_id = $model->brand_id;
                $goods->goods_category_id = $goods_categoryForm->parent_id;
                $goods->market_price = $model->market_price;
                $goods->shop_price = $model->shop_price;
                $goods->stock = $model->stock;
                $goods->status = $model->status;
                $goods->is_on_sale = $model->is_on_sale;
                $goods->create_time = time();
                $goods->view_times = $model->view_times;
                $goods_content->content = $goods_introForm->content;

                //判断是否保存成功,如果保存成功则记录一次商品添加的次数
                if($goods->save(false))
                {
                    $goods_content->goods_id = $goods->id;
                   // var_dump($goods_content->content);die;
                    $goods_content->save();

                    //查询当天的商品添加次数
                   // $day = date('Y-m-d',time());
                   // $goods_day = Goods_day_count::find()->where(['day'=>$day])->one();

                    if($goods_day!=null)
                    {
                        //有商品次数
                        $goods_day->count +=1;
                        $goods_day->save();
                    }else{
                        //无商品次数
                        $goods_day = new Goods_day_count();
                        $goods_day->count +=1;
                        $goods_day->day = $day;
                        $goods_day->save();
                    }
                    //对该次数进行+1后保存

                }
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect(["goods/img?id=$goods->id"]);
            }
        }
        //跳转
        return $this->render('add',['model'=>$model,'goods_categoryForm'=>$goods_categoryForm,'brand'=>$brand,'gc'=>$gc,'goods_introForm'=>$goods_introForm]);
    }

    //>>3.修改商品
    public function actionEdit()
    {
        //创建商品详情表单模型
        $goods_introForm = new Goods_introForm();
        //获取品牌分类
        $brand = Brand::find()->select(['id','name'])->where(['status'=>1])->all();
        $brand = ArrayHelper::map($brand,'id','name');
        $model = new GoodsForm();
        //接收id
        $request = new Request();
        $id = $request->get('id');
        //根据id查询数据
        $row = Goods::findOne($id);
        //根据id查询商品详情
        $content = Goods_intro::find()->where(['goods_id'=>$id])->one();
        if($content!=null)
        {
            //如果有详情
            $goods_introForm->content = $content->content;

        }
        $model->name = $row->name;
        $model->sn = $row->sn;
        $model->logo = $row->logo;
        $model->brand_id = $row->brand_id;
        $model->goods_category_id = $row->goods_category_id;
        $model->market_price = $row->market_price;
        $model->shop_price = $row->shop_price;
        $model->stock = $row->stock;
        $model->status = $row->status;
        $model->is_on_sale = $row->is_on_sale;
        $model->create_time = $row->create_time;
        $model->view_times = $row->view_times;

        if($request->isPost)
        {
            //接收修改后的表单数据
            $model->load($request->post());
            $goods_introForm->load($request->post());
            //将文件封装成对象
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            //验证数据
            if($model->validate()&&$goods_introForm->validate())
            {   //判断是否有图片上传
                if ($model->imgFile!=null)
                {
                    //获取文件扩展名
                    $ext = $model->imgFile->extension;
                    //拼接文件路径
                    $fileName = '/upload/'.uniqid().'.'.$ext;
                    //指定文件保存的路径
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,0);
                    $row->logo = $fileName;
                }
                //将文件路径保存到数据库
                $row->name = $model->name;
                $row->sn = $model->sn;
                $row->brand_id = $model->brand_id;
                $row->goods_category_id = $model->goods_category_id;
                $row->market_price = $model->market_price;
                $row->shop_price = $model->shop_price;
                $row->stock = $model->stock;
                $row->status = $model->status;
                $row->is_on_sale = $model->is_on_sale;
                $row->view_times = $model->view_times;
                $content->content = $goods_introForm->content;
                $row->save(false);
                $content->save();
                //跳转
                \Yii::$app->session->setFlash('success','修改成功');
                $this->redirect(['goods/index']);
            }

        }

        //将数据回显到表单
        return $this->render('edit',['model'=>$model,'brand'=>$brand,'goods_introForm'=>$goods_introForm]);
    }

    //>>4.删除品牌
    public function actionDel()
    {
        //接收id
        $request = new Request();
        $id = $request->post('id');
        //根据id查询数据
        $goods = Goods::findOne($id);
        //删除数据
        $goods->status = -1;
        $result = $goods->save(false);
        //响应ajax
        if($result)
        {
            echo '1';
        }

    }

    //>>5.回收列表
    public function actionRecycle()
    {
        //实例化分页组件
        $pager = new Pagination();
        //查询数据
        $goods = Goods::find();
        //每页显示条数据
        $pager->pageSize = 10;
        //总记录数据
        $pager->totalCount = $goods->where(['status'=>['-1']])->count();
        //设置偏移量
        $rows = $goods->where(['status'=>['-1']])->limit($pager->limit)->offset($pager->offset)->all();
        //分配数据
        return $this->render('recycle',['rows'=>$rows,'pager'=>$pager]);
    }

    //>>6.恢复
    public function actionRestore()
    {
        //接收id
        $request = new Request();
        $id = $request->post('id');
        //根据id查询数据
        $row = Goods::findOne($id);
        //恢复数据
        $row->status = 1;
        $result = $row->save(false);

        //响应ajax
        if($result)
        {
            echo '1';
        }
    }

    //>>7.永久删除
    public function actionDelete()
    {
        //接收id
        $request = new Request();
        $id = $request->post('id');
        //根据id查询数据

        $row = Goods::findOne($id);
        //删除数据
        $result = $row->delete();

        //$intro = Goods_intro::find()->where(['goods_id'=>$id])->one();
        //$intro->delete();
        //响应ajax
        if($result)
        {

            echo '1';
        }
    }

    //>>8.商品图片列表
    public function actionImg()
    {
        //商品图片的添加
        $request = new Request();
        $id = $request->get('id');
        $rows = Goods_gallery::find()->where(['goods_id'=>$id])->all();
       // var_dump($rows);die;
        $model = new Goods_galleryForm();
        $model->goods_id = $id;

        return $this->render('img',['model'=>$model,'rows'=>$rows]);
    }

    //>>9.商品图片添加
    public function actionAdd_img()
    {
        $request = new Request();
        $id = $request->get('goods_id');

        $imgFile = UploadedFile::getInstanceByName('file');
        if($imgFile)
        {
            //获取文件扩展名
            $ext = $imgFile->extension;
            //拼接文件路径
            $fileName = '/upload/'.uniqid().'.'.$ext;
            //指定文件保存的路径
            $imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,0);
        }
        $goods_gallery = new goods_gallery();
        $goods_gallery->path = $fileName;
        $goods_gallery->goods_id = $id;
        if($goods_gallery->save())
        {

            return json_encode(['url'=>$fileName]);
        }

    }

    //>>10.商品图片删除
    public function actionDel_img()
    {
          $request = new Request();
          $id = $request->post('id');
          $row = Goods_gallery::findOne($id);
          $result = $row->delete();
          if($result)
          {
              echo '1';
          }

    }

    //>>11.商品详情'
    public function actionIntro()
    {
        //根据id获取商品信息
        $request = new Request();
        $id = $request->get('id');
        $goods = Goods::find()->where(['id'=>$id])->one();
        //获取商品详情内容
        $content = Goods_intro::find()->where(['goods_id'=>$id])->one();
        if($content==null)
        {
            $content  = new Goods_intro();
            $content->content = "<h3>您没有添加商品详情!<a href='edit?id=$id'>点击添加详情</a></h3>";

        }
        return $this->render('intro',['goods'=>$goods,'content'=>$content]);
    }

    //>>商品搜索
    public function actionSearch()
    {
        //实例化分页组件
        $pager = new Pagination();
        //每页显示条数据
        $pager->pageSize = 5;
        //查询数据
        $goods = Goods::find()->where(['status'=>1]);
        //接收数据
        $request = new Request();
        $name = $request->get('name');
        $sn = $request->get('sn');
        $shop_price = $request->get('shop_price');
        $shop_price_max = $request->get('shop_price_max');
        if($name)
        {
            $goods->andWhere(['like','name',$name]);
        }
        if($sn)
        {
            $goods->andWhere(['like','sn',$sn]);
        }
        if($shop_price)
        {
            $goods->andWhere(['>','shop_price',$shop_price]);
        }
        if($shop_price_max)
        {
            $goods->andWhere(['<','shop_price',$shop_price_max]);
        }
        //总的记录数
        $pager->totalCount = $goods->count();
        //设置偏移量
        $rows = $goods->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);

    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }

    Public function behaviors()
    {
        Return
            [
                [
                    'class'=>AdminFilter::className(),
                    //'only'=>['add'],//指定执行的过虑器的操作,
                    'except'=>['upload','add_img','img'],//除了这些操作,都有效
                ]

            ];
    }
}