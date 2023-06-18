<?php
namespace app\index\controller;

use think\Controller;

class Login extends Controller
{
    public function login()
    {
        return $this->fetch();
    }

    public function checkLogin()
    {
        $username = $_POST["username"];
        if($username != "admin")
        {
            return "用户名不存在！";
        }
        $password = $_POST["password"];
        if($password != "admin")
        {
            return "密码错误！";
        }

        session("login", true);
        return "";
    }

    public function logout()
    {
        session("login", null);
        return "ok";
    }
}