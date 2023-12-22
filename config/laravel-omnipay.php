<?php

return [
    // Cấu hình cho các cổng thanh toán tại hệ thống của bạn, các cổng không xài có thể xóa cho gọn hoặc không điền.
    // Các thông số trên có được khi bạn đăng ký tích hợp.

    'gateways' => [
        'MoMoAIO' => [
            'driver' => 'MoMo_AllInOne',
            'options' => [
                'accessKey' => '1FTioTv10AYRTDy5',
                'secretKey' => 'oyoX0Lh0iCTk9G0bOg1y99071tQbJIwb',
                'partnerCode' => 'MOMO8BRH20210621',
                'testMode' => true,
            ],
        ],
    ],
];
