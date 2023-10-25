<?php

return [
    // ! USER
    [
        'name' => 'Người dùng',
        'flag' => 'user.all',
        'parent_flag' => 'dashboard',
    ],
    [
        'name' => 'Xem danh sách',
        'flag' => 'user.index',
        'parent_flag' => 'user.all',
    ],
    [
        'name' => 'Tạo mới',
        'flag' => 'user.create',
        'parent_flag' => 'user.all',
    ],
    [
        'name' => 'Chỉnh sửa',
        'flag' => 'user.edit',
        'parent_flag' => 'user.all',
    ],
    [
        'name' => 'Xóa bỏ',
        'flag' => 'user.delete',
        'parent_flag' => 'user.all',
    ],
    // ! ROLE
    [
        'name' => 'Phân quyền',
        'flag' => 'role.all',
        'parent_flag' => 'dashboard',
    ],
    [
        'name' => 'Xem danh sách',
        'flag' => 'role.index',
        'parent_flag' => 'role.all',
    ],
    [
        'name' => 'Tạo mới',
        'flag' => 'role.create',
        'parent_flag' => 'role.all',
    ],
    [
        'name' => 'Chỉnh sửa',
        'flag' => 'role.edit',
        'parent_flag' => 'role.all',
    ],
    [
        'name' => 'Xóa bỏ',
        'flag' => 'role.destroy',
        'parent_flag' => 'role.all',
    ],
];
