<?php

return [
    'required' => ':attribute は必須です。',
    'email' => ':attribute は有効なメールアドレス形式で入力してください。',
    'confirmed' => ':attribute の確認が一致しません。',
    'min' => [
        'string' => ':attribute は :min 文字以上で入力してください。',
    ],
    'max' => [
        'string' => ':attribute は :max 文字以内で入力してください。',
    ],

    'attributes' => [
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password_confirmation' => 'パスワード確認',
        'name' => '名前',
    ],
];

