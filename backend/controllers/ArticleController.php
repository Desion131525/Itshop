<?php
namespace backend\controllers;
use backend\filters\AdminFilter;
use backend\models\Article;
use backend\models\Article_detail;
use backend\models\Article_detailForm;
use backend\models\ArticleForm;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 9:42
 */
class ArticleController extends Controller
{
    //>>1.显示文章列表
    public function actionIndex()
    {
        //实例化分页组件
        $pager = new Pagination();
        //查询数据
        $article = Article::find();
        //每页显示条数据
        $pager->pageSize = 3;
        //总记录数据
        $pager->totalCount = $article->where(['status'=>['1','0']])->count();
        //设置偏移量
        $rows = $article->where(['status'=>['1','0']])->limit($pager->limit)->offset($pager->offset)->all();
        //分配数据
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }

    //>>2.添加文章
    public function actionAdd()
    {
        //显示表单
        $model = new ArticleForm();
        $article_detailForm = new Article_detailForm();
        $request = new Request();
        if($request->isPost)
        {
            $article = new Article();
            $article_detail = new Article_detail();
            //接收数据
            $model->load($request->post());
            //接收文章详情
            $article_detailForm->load($request->post());
            //验证数据
            if($model->validate())
            {
                //保存数据
                $article->name = $model->name;
                $article->intro = $model->intro;
                $article->status = $model->status;
                $article->create_time = time();
                $article->article_category_id = $model->article_category_id;
                $article_detail->content = $article_detailForm->content;

                if( $article->save())
                {
                   $article_id = \Yii::$app->db->getLastInsertID();
                    $article_detail->article_id = $article_id;
                    $article_detail->save();
                }else{
                    die('添加失败');
                }

                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect(['article/index']);
            }

        }

        return $this->render('add',['model'=>$model,'article_detailForm'=>$article_detailForm]);
    }

    //修改文章
    public function actionEdit()
    {
        $model = new ArticleForm();
        $article_detailForm = new Article_detailForm();
        //接收id
        $request = new Request();
        $id = $request->get('id');
        //根据id查询数据
        $a_row = Article::findOne($id);
        $ad_row = Article_detail::findOne($id);
        $model->name = $a_row->name;
        $model->intro = $a_row->intro;
        $model->status = $a_row->status;
        $article_detailForm->content = $ad_row->content;
        if($request->isPost)
        {
            //接收修改后的数据
            $model->load($request->post());
            $article_detailForm->load($request->post());
            //验证数据
            if($model->validate())
            {
                //保存数据
                $a_row->name = $model->name;
                $a_row->intro = $model->intro;
                $a_row->status = $model->status;
                $ad_row->content = $article_detailForm->content;
                $a_row->save();
                $ad_row->save();
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect(['article/index']);
            }

        }

        //将数据回显到表单
        return $this->render('edit',['model'=>$model,'article_detailForm'=>$article_detailForm]);
    }


    //>>4.删除文章
    public function actionDel()
    {
        //接收id
        $request = new Request();
        $id = $request->post('id');

        //根据id查询数据
        $a_row = Article::findOne($id);
        //删除数据
        $a_row->status = -1;
        $result = $a_row->save(false);

        //响应ajax
        if($result)
        {
            echo '1';
        }

    }

    Public function behaviors()
    {
        Return
            [
                [
                    'class'=>AdminFilter::className(),
                    //'only'=>['add'],//指定执行的过虑器的操作,
                    //'except'=>['login'],//除了这些操作,都有效
                ]

            ];
    }
}