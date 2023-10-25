<?php

return [
    // ! POST
    [
        'name' => 'Bài viết',
        'flag' => 'post.all',
        'parent_flag' => 'dashboard',
    ],
    [
        'name' => 'Xem danh sách',
        'flag' => 'post.index',
        'parent_flag' => 'post.all',
    ],
    [
        'name' => 'Tạo mới',
        'flag' => 'post.create',
        'parent_flag' => 'post.all',
    ],
    [
        'name' => 'Chỉnh sửa',
        'flag' => 'post.edit',
        'parent_flag' => 'post.all',
    ],
    [
        'name' => 'Xóa bỏ',
        'flag' => 'post.delete',
        'parent_flag' => 'post.all',
    ],
];
