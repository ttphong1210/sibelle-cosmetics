<?php

namespace App\Jobs;


use App\Services\EmailService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EmailService $emailService)
    {
        try {
            $customer = $this->data['customer'];
            $order = $this->data['order'];
            $fullAddress = $this->data['fullAddress'];
            $cart = $this->data['cart'];
            $emailData = [
                'customer_name' => $customer['name'],
                'order_code' => $order->order_code,
                'notes' => $customer['notes'],
                'fullAddress' => $fullAddress,
                'cartInfo' => $cart,
                'total' => $order->total,
                'order_date' => $order->date_order->format('d/m/Y H:i'),
                'estimated_delivery' => now()->addDays(3)->format('d/m/Y'),
            ];
            $emailService->sendEmail(
                'frontend.email',
                $emailData,
                $customer['email'],
                $customer['name'],
                'Xác nhận đơn hàng #' . $order->order_code
            );
        } catch (Exception $e) {
            Log::error('Failed to send order confirmation email', [
                'error' => $e->getMessage(),
                'order_code' => $this->data['order']->order_code ?? 'unknown'
            ]);
            throw $e;
        }
    }
    /**
     * Handle a job failure.
     */
    public function failed(Exception $e)
    {
        Log::error('Order confirmation email jod failed', [
            'error' => $e->getMessage(),
            'order_code' => $this->data['order']->order_code ?? 'unknown'
        ]);
    }
}

class SendNewOrderNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EmailService $emailService)
    {
        try {
            $customer = $this->data['customer'];
            $order = $this->data['order'];
            $fullAddress = $this->data['fullAddress'];
            $cartInfo = $this->data['cart'];
            
            $emailData = [
                'customer_name' => $customer['name'],
                'customer_phone' => $customer['number_phone'],
                'customer_email' => $customer['email'],
                'order_code' => $order->order_code,
                'notes' => $order->notes,
                'fullAddress' => $fullAddress,
                'cartInfo' => $cartInfo,
                'total' => $order->total,
                'order_date' => $order->date_order->format('d/m/Y H:i'),
                'payment_method' => $order->order_payment
            ];

            $managerEmail = config('mail.manager_email', 'huuduongn91@gmail.com');
            $managerName = config('mail.manager_name', 'Manager Si.Belle Cosmetics');

            $emailService->sendEmail(
                'frontend.new_order_notification',
                $emailData,
                $managerEmail,
                $managerName,
                'Đơn hàng mới #' . $order->order_code
            );
            Log::info('New order notification email sent successfully', [
                'order_code' => $order->order_code,
                'managerEmail' => $managerEmail
            ]);
        } catch (Exception $e) {
            Log::error('Failed to send new order notification email', [
                'error' => $e->getMessage(),
                'order_code' => $this->data['order']->order_code ?? 'unknown'
            ]);

            throw $e;
        }
    }
    /**
     * Handle a job failure.
     */
    public function failed(Exception $exception)
    {
        Log::error('New order notification email job failed', [
            'error' => $exception->getMessage(),
            'order_code' => $this->data['order']->order_code ?? 'unknown'
        ]);
    }
}
