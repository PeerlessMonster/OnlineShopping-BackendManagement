<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;

class Good extends Controller
{
    public function good()
    {
        if (empty(session('login'))) {
            echo "<script type='text/javascript'>".
                "alert('请先登录！');".
                "location.href='../login/login';".
                "</script>";
        }

        $result = Db::table('goods')->paginate(6, false);
        $this->assign('goodsList', $result);
        return $this->fetch();
    }

    public function addGood(Request $request)
    {
        $name = $_POST["name"];

        $priceStr = $_POST["price-integer"] . "." . $_POST["price-fraction"];
        $price = floatval($priceStr);
        
        $numberStr = $_POST["number"];
        $number = intval($numberStr);

        $weightStr = $_POST["weight"];
        $weight = floatval($weightStr);

        $pic = $request->file('pic');
        $info = $pic->rule(function() {
            $latestId = Db::table('goods')->max('id');
            return 'goods' . ($latestId + 1);
        })->move(
            // $_SERVER['CONTEXT_DOCUMENT_ROOT'] . DS . 'phpBigHomework' . DS . 'pic'
            ROOT_PATH . DS . 'public' . DS . 'uploads'
        );
        $newPicName = explode('.', $info->getSavename())[0];
        $newPicId = intval(substr($newPicName, 5));

        $data = [
            'id' => $newPicId,
            'name' => $name,
            'price' => $price,
            'num' => $number,
            'weight' => $weight,
            'pic_id' => $newPicName
        ];
        Db::table('goods')->insert($data);
        echo "<script type='text/javascript'>".
                "alert('添加成功！');".
                "location.href='good';".
                "</script>";
    }

    public function updateGood(Request $request)
    {
        $name = $_POST["name"];

        $priceStr = $_POST["price-integer"] . "." . $_POST["price-fraction"];
        $price = floatval($priceStr);
        
        $numberStr = $_POST["number"];
        $number = intval($numberStr);

        $weightStr = $_POST["weight"];
        $weight = floatval($weightStr);

        $pic = $request->file('pic');
        $pic->rule(function() {
            $thisIdStr = $_POST["good-id"];
            return 'goods' . $thisIdStr;
        })->move(
            // $_SERVER['CONTEXT_DOCUMENT_ROOT'] . DS . 'phpBigHomework' . DS . 'pic'
            ROOT_PATH . DS . 'public' . DS . 'uploads'
        );

        Db::table('goods')->where('id', intval($_POST["good-id"]))->update([
            'name' => $name,
            'price' => $price,
            'num' => $number,
            'weight' => $weight
        ]);
        echo "<script type='text/javascript'>".
                "alert('修改成功！');".
                "location.href='good';".
                "</script>";
    }

    public function deleteGood()
    {
        $deleteGoodIds = $_POST["data"];
        Db::table('goods')->delete($deleteGoodIds);
        return "ok";
    }
}