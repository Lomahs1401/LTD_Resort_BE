<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list_avatars = [
            'gs://ltd-resort.appspot.com/avatars/aether.png',
            'gs://ltd-resort.appspot.com/avatars/albedo.png',
            'gs://ltd-resort.appspot.com/avatars/aloy.png',
            'gs://ltd-resort.appspot.com/avatars/amber.png',
            'gs://ltd-resort.appspot.com/avatars/ayaka.png',
            'gs://ltd-resort.appspot.com/avatars/ayato.png',
            'gs://ltd-resort.appspot.com/avatars/barbara.png',
            'gs://ltd-resort.appspot.com/avatars/beidou.png',
            'gs://ltd-resort.appspot.com/avatars/bennett.png',
            'gs://ltd-resort.appspot.com/avatars/childe.png',
            'gs://ltd-resort.appspot.com/avatars/chongyun.png',
            'gs://ltd-resort.appspot.com/avatars/diluc.png',
            'gs://ltd-resort.appspot.com/avatars/diona.png',
            'gs://ltd-resort.appspot.com/avatars/eula.png',
            'gs://ltd-resort.appspot.com/avatars/fischl.png',
            'gs://ltd-resort.appspot.com/avatars/ganyu.png',
            'gs://ltd-resort.appspot.com/avatars/gorou.png',
            'gs://ltd-resort.appspot.com/avatars/heizou.png',
            'gs://ltd-resort.appspot.com/avatars/hutao.png',
            'gs://ltd-resort.appspot.com/avatars/itto.png',
            'gs://ltd-resort.appspot.com/avatars/jean.png',
            'gs://ltd-resort.appspot.com/avatars/kaeya.png',
            'gs://ltd-resort.appspot.com/avatars/kazuha.png',
            'gs://ltd-resort.appspot.com/avatars/keqing.png',
            'gs://ltd-resort.appspot.com/avatars/klee.png',
            'gs://ltd-resort.appspot.com/avatars/kokomi.png',
            'gs://ltd-resort.appspot.com/avatars/kujou sara.png',
            'gs://ltd-resort.appspot.com/avatars/kuki shinobu.png',
            'gs://ltd-resort.appspot.com/avatars/lisa.png',
            'gs://ltd-resort.appspot.com/avatars/lumine.png',
            'gs://ltd-resort.appspot.com/avatars/mona.png',
            'gs://ltd-resort.appspot.com/avatars/ningguang.png',
            'gs://ltd-resort.appspot.com/avatars/noelle.png',
            'gs://ltd-resort.appspot.com/avatars/qiqi.png',
            'gs://ltd-resort.appspot.com/avatars/raiden shogun.png',
            'gs://ltd-resort.appspot.com/avatars/razor.png',
            'gs://ltd-resort.appspot.com/avatars/rosaria.png',
            'gs://ltd-resort.appspot.com/avatars/sayu.png',
            'gs://ltd-resort.appspot.com/avatars/shenhe.png',
            'gs://ltd-resort.appspot.com/avatars/sucrose.png',
            'gs://ltd-resort.appspot.com/avatars/thoma.png',
            'gs://ltd-resort.appspot.com/avatars/venti.png',
            'gs://ltd-resort.appspot.com/avatars/xiangling.png',
            'gs://ltd-resort.appspot.com/avatars/xiao.png',
            'gs://ltd-resort.appspot.com/avatars/xingqiu.png',
            'gs://ltd-resort.appspot.com/avatars/xinyan.png',
            'gs://ltd-resort.appspot.com/avatars/yae miko.png',
            'gs://ltd-resort.appspot.com/avatars/yanfei.png',
            'gs://ltd-resort.appspot.com/avatars/yelan.png',
            'gs://ltd-resort.appspot.com/avatars/yoimiya.png',
            'gs://ltd-resort.appspot.com/avatars/yunjin.png',
            'gs://ltd-resort.appspot.com/avatars/zhongli.png',
        ];

        define('TOTAL_ACCOUNT', count($list_avatars));

        for ($i = 0; $i < TOTAL_ACCOUNT; $i++) {
            Account::factory()->create([
                'username' => fake()->userName(),
                'email' => fake()->safeEmail(),
                'password' => Hash::make('123'),
                'avatar' => $list_avatars[$i],
                'enabled' => fake()->boolean(100),
                'role_id' => fake()->randomElement([1, 2, 3]),
                'reset_code'=> null,
                'reset_code_expires_at'=>null,
                'reset_code_attempts'=>null
            ]);
        }
    }
}
