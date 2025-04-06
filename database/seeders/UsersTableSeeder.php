<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
// DB機能新規追加用use宣言
use Illuminate\Support\Facades\Hash;
// パスワード追加のHash宣言
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
             DB::table('users')->insert([
        ['over_name'=>'武田',
        'under_name'=>'楽斗',
        'over_name_kana'=>'タケダ',
        'under_name_kana'=>'ガクト',
        'mail_address'=>'g.takeda0821@gmail.com',
        'sex'=>'1',
        'birth_day'=>'2002-08-21',
        'role'=>'1',
        'password'=>Hash::make('1999130821'),],
     ]);

    }
}
