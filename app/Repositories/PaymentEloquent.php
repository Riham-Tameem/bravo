<?php

namespace App\Repositories;

use App\Http\Controllers\Api\BaseController;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Api\Details;
use Illuminate\Support\Facades\Input;
use Redirect;
use URL;

class PaymentEloquent extends BaseController
{
    public $_api_context;

    public function __construct()
    {
        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);

        //  $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function payWithpaypal(array $data)
    {
        //  $order=new \PayPal\Api\Order();
        $order = Order::where('id', $data['id'])->first();
        $result = $order->result;
        $total = $order->total;
        $ship_tax = 0;
        $ship_cost = $order->delivery;
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

//        $order_products=OrderProduct::where('order_id',$data['id'])->select('quantity','cost','product_id')->get();
        $item_list = new ItemList();
        $items = [];
//dd($order->products);

        $total = 0;
        foreach ($order->products as $order_product) {
            $amountToBePaid = (double)$order_product->pivot->cost;
            //$amountToBePaid =(sprintf('%0.2f', $order_product->pivot->cost));
            $name = $order_product->translation()->name;
            $item = new Item();
            $item->setName($name)
                ->setCurrency('USD')
                ->setQuantity($order_product->pivot->quantity)
                //->setPrice(sprintf('%0.2f', $order_product->pivot->cost));
                ->setPrice($amountToBePaid);
            $items[] = $item;
            // $item_list->setItems(array($item));

            $total += $order_product->pivot->quantity * $amountToBePaid;
        }

        $item_list->setItems($items);
        //dd($item_list);
        /* $item1 = new Item();
              $item1->setName('Ground Coffee 40 oz')
                  ->setCurrency('USD')
                  ->setQuantity(1)
                  ->setPrice(7.5);
              $item2 = new Item();
              $item2->setName('Granola bars')
                  ->setCurrency('USD')
                  ->setQuantity(5)
                  ->setPrice(2);
              $item_list = new ItemList();
              $item_list->setItems(array($item1, $item2));
      */

        $details = new Details();
        $details->setShipping($ship_cost)
            ->setTax($ship_tax)
            ->setSubtotal($total);

        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($result)
            ->setDetails($details);
        //dd($item_list);
        /*  $amount = new Amount();
          $amount->setCurrency('USD')
                 ->setTotal($result);*/
        $redirect_urls = new RedirectUrls();
        /** Specify return URL **/
        $redirect_urls->setReturnUrl(URL::route('status'))
            ->setCancelUrl(URL::route('status'));
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Your transaction description');
        //dd($transaction);
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        //dd($redirect_urls);
        //$request = clone $payment;
        //dd($request);
        // dd($payment->create($this->_api_context));
        try {
            //   dd($payment->create($this->_api_context));
            // $payment->create($this->_api_context);
            $payment->create($this->_api_context);
            //} catch (  \Exception $ex
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            //dd($ex);
            if (\Config::get('app.debug')) {
                /* \Session::put('error', 'Connection timeout');
                 return Redirect::route('/');*/
                return $ex->getMessage();
            } else {
                /*  \Session::put('error', 'Some error occur, sorry for inconvenient');
                  return Redirect::route('/');*/
                return $ex->getMessage();
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        /** add payment ID to session **/
        //  dd($payment->getId());
        \Session::put('paypal_payment_id', $payment->getId());
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            dd($redirect_url);
            return Redirect::away($redirect_url);
        }

        \Session::put('error', 'Unknown error occurred');
        return Redirect::route('/');
    }

    public function getPaymentStatus(array $request)
    {

        /** Get the payment ID before session clear **/
        $payment_id = $request['paymentId'];
        /** clear the session payment ID **/
//        Session::forget('paypal_payment_id');
        if (empty($request['PayerID']) || empty($request['token'])) {
            session()->flash('error', 'Payment failed');
            return Redirect::route('/');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request['PayerID']);
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {

            return $this->sendResponse('Payment success', []);
            //   session()->flash('success', 'Payment success');
//save cod to payment
            //

//            return Redirect::route('/');
        }

        return $this->sendError('Payment failed');
//        session()->flash('error', 'Payment failed');
        return Redirect::route('/');
    }

}
