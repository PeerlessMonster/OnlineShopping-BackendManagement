<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Order extends Controller
{
    public function order($status = 0)
    {
        if (empty(session('login'))) {
            echo "<script type='text/javascript'>".
                "alert('请先登录！');".
                "location.href='../login/login';".
                "</script>";
        }

        $sql = "SELECT o.id AS orderId, u.name AS username, u.`pic_id` AS userPicId, g.name AS goodName, g.price AS goodPrice, g.`pic_id` AS goodPicId, o.num AS num, a.name AS consigneeName, address, phone, `is_pay`, `is_receive`, `is_comment`" .
            " FROM `order` o, users u, goods g, address a" .
            " WHERE o.`user_id`=u.id" .
            " AND o.`goods_id`=g.id" .
            " AND o.`address_id`=a.id";
        if ($status > 0 && $status <= 4) {
            $status = $_REQUEST['status'];
            if($status == 1) {
                $sql = $sql . " AND is_pay=0";
            } elseif($status == 2) {
                $sql = $sql . " AND (is_pay=1 AND is_receive=0)";
            } elseif($status == 3) {
                $sql = $sql . " AND (is_receive=1 AND is_comment=0)";
            } elseif($status == 4) {
                $sql = $sql . " AND is_comment=1";
            }
        }
        $sql = $sql . ";";
        $result = Db::query($sql);

        $this->assign("ordersList", $result);
        return $this->fetch();
    }

    public function changeOrderStatus()
    {
        $orderId = intval($_POST['data']);
        Db::table('order')->where('id', $orderId)->update([
            'is_receive' => 1
        ]);
        return 'ok';
    }
}