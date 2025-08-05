<!DOCTYPE html>
<html>

<head>
    <title>Thông báo đơn hàng mới</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style type="text/css">
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        @media screen and (max-width: 480px) {
            .mobile-hide {
                display: none !important;
            }

            .mobile-center {
                text-align: center !important;
            }
        }

        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>
</head>

<body style="margin: 0 !important; padding: 0 !important; background-color: #eeeeee;" bgcolor="#eeeeee">

    <div
        style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Open Sans, Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
        Bạn có đơn hàng mới từ {{$customer_name}} với mã đơn hàng {{$order_code}}.
    </div>

    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">

                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                    <tr>
                        <td align="center" valign="top" style="font-size:0; padding: 35px;" bgcolor="#FF6B35">

                            <div
                                style="display:inline-block; max-width:50%; min-width:100px; vertical-align:top; width:100%;">
                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                    style="max-width:300px;">
                                    <tr>
                                        <td align="left" valign="top"
                                            style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 48px;"
                                            class="mobile-center">
                                            <h1 style="font-size: 36px; font-weight: 800; margin: 0; color: #ffffff;">
                                                SI.BELLE Cosmetic</h1>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div style="display:inline-block; max-width:50%; min-width:100px; vertical-align:top; width:100%;"
                                class="mobile-hide">
                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                    style="max-width:300px;">
                                    <tr>
                                        <td align="right" valign="top"
                                            style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 24px;">
                                            <p style="font-size: 18px; font-weight: 400; margin: 0; color: #ffffff;">
                                                🔔 ĐƠN HÀNG MỚI
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 35px 35px 20px 35px; background-color: #ffffff;"
                            bgcolor="#ffffff">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                                style="max-width:600px;">
                                <tr>
                                    <td align="center"
                                        style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;">
                                        <img src="https://img.icons8.com/color/100/000000/shopping-cart-loaded.png"
                                            width="125" height="120" style="display: block; border: 0px;" /><br>
                                        <h2
                                            style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;">
                                            Đơn hàng mới từ khách hàng!
                                        </h2>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left"
                                        style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                                        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                                            <h3 style="color: #333333; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">
                                                Thông tin đơn hàng
                                            </h3>
                                            <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #555555; margin: 8px 0;">
                                                <strong>Mã đơn hàng:</strong> {{$order_code}}
                                            </p>
                                            <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #555555; margin: 8px 0;">
                                                <strong>Ngày đặt:</strong> {{$order_date}}
                                            </p>
                                            <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #555555; margin: 8px 0;">
                                                <strong>Phương thức thanh toán:</strong> {{$payment_method}}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 0 35px 20px 35px; background-color: #ffffff;" bgcolor="#ffffff">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                                <tr>
                                    <td align="left">
                                        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                                            <h3 style="color: #333333; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">
                                                Thông tin khách hàng
                                            </h3>
                                            <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #555555; margin: 8px 0;">
                                                <strong>Tên khách hàng:</strong> {{$customer_name}}
                                            </p>
                                            <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #555555; margin: 8px 0;">
                                                <strong>Số điện thoại:</strong> <a href="tel:{{$customer_phone}}" style="color: #FF6B35; text-decoration: none;">{{$customer_phone}}</a>
                                            </p>
                                            <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #555555; margin: 8px 0;">
                                                <strong>Email:</strong> <a href="mailto:{{$customer_email}}" style="color: #FF6B35; text-decoration: none;">{{$customer_email}}</a>
                                            </p>
                                            <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #555555; margin: 8px 0;">
                                                <strong>Địa chỉ giao hàng:</strong> 
                                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($fullAddress) }}" target="_blank" rel="noopener noreferrer" style="color: #FF6B35; text-decoration: none;">
                                                    {{$fullAddress}}
                                                </a>
                                            </p>
                                            @if($notes)
                                            <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #555555; margin: 8px 0;">
                                                <strong>Ghi chú:</strong> {{$notes}}
                                            </p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 0 35px 20px 35px; background-color: #ffffff;" bgcolor="#ffffff">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                                <tr>
                                    <td align="left" style="padding-top: 20px;">
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td width="10%" align="center" bgcolor="#FF6B35"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; color: #ffffff;">
                                                    STT
                                                </td>
                                                <td width="60%" align="left" bgcolor="#FF6B35"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; color: #ffffff;">
                                                    Sản phẩm
                                                </td>
                                                <td width="15%" align="center" bgcolor="#FF6B35"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; color: #ffffff;">
                                                    SL
                                                </td>
                                                <td width="25%" align="right" bgcolor="#FF6B35"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; color: #ffffff;">
                                                    Giá tiền
                                                </td>
                                            </tr>
                                            @foreach($cartInfo as $index => $item)
                                            <tr style="border-bottom: 1px solid #eeeeee;">
                                                <td width="10%" align="center"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px;">
                                                    {{$index + 1}}
                                                </td>
                                                <td width="60%" align="left"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px;">
                                                    {{$item['name']}}
                                                </td>
                                                <td width="15%" align="center"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px;">
                                                    {{$item['quantity'] ?? 1}}
                                                </td>
                                                <td width="25%" align="right"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px;">
                                                    {{number_format($item['price'],0,',','.')}}₫
                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="padding-top: 20px;">
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td width="75%" align="right"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 800; line-height: 24px; padding: 15px 10px; border-top: 3px solid #FF6B35; border-bottom: 3px solid #FF6B35; color: #FF6B35;">
                                                    TỔNG TIỀN:
                                                </td>
                                                <td width="25%" align="right"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 800; line-height: 24px; padding: 15px 10px; border-top: 3px solid #FF6B35; border-bottom: 3px solid #FF6B35; color: #FF6B35;">
                                                    {{number_format($total,0,',','.')}}₫
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 35px; background-color: #ffffff;" bgcolor="#ffffff">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                                style="max-width:600px;">
                                <tr>
                                    <td align="center" style="padding: 20px 0;">
                                        <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 20px; border-radius: 8px;">
                                            <h3 style="color: #856404; margin: 0 0 10px 0; font-size: 16px; font-weight: 700;">
                                                ⚠️ Lưu ý quan trọng
                                            </h3>
                                            <p style="font-size: 14px; font-weight: 400; line-height: 20px; color: #856404; margin: 0;">
                                                Vui lòng liên hệ khách hàng để xác nhận đơn hàng và sắp xếp giao hàng sớm nhất.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding: 20px 0;">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td align="center" style="padding: 0 10px;">
                                                    <a href="tel:{{$customer_phone}}" 
                                                       style="background-color: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: 600; display: inline-block;">
                                                        📞 Gọi ngay
                                                    </a>
                                                </td>
                                                <td align="center" style="padding: 0 10px;">
                                                    <a href="mailto:{{$customer_email}}" 
                                                       style="background-color: #007bff; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: 600; display: inline-block;">
                                                        ✉️ Gửi email
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 20px 35px 35px 35px; background-color: #f8f9fa;" bgcolor="#f8f9fa">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                                style="max-width:600px;">
                                <tr>
                                    <td align="center"
                                        style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 20px;">
                                        <p style="font-size: 14px; font-weight: 400; line-height: 20px; color: #777777; margin: 0;">
                                            Email này được gửi tự động từ hệ thống SI.BELLE Cosmetic.<br>
                                            Vui lòng không trả lời email này.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>