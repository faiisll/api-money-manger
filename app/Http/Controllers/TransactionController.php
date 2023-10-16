<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Transaction;
use App\Helpers\ResponseHelper;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use App\Models\Wallet;
class TransactionController extends Controller
{
    public function index(Request $req){
        $userId = auth()->id();
        $params = [
            'dateFrom' => $req->query('dateFrom'),
            'dateTo' => $req->query('dateTo'),
            'groupBy' => $req->query('groupBy'),
            'walletId' => $req->query('walletId'),
            'limit' => $req->query('limit')
        ];
        $query = Transaction::query();
        $limit = $params['limit'] ? $params['limit'] : 2;
        
        $query->when($params['dateFrom'] !== null, function ($q)use($params){
            $dateFrom = Carbon::parse($params['dateFrom']);
            return $q->whereDate('date', '>=' , $dateFrom->format('Y-m-d'));
        });

        $query->when($params['dateTo'] !== null, function ($q)use($params){
            $dateTo = Carbon::parse($params['dateTo']);
            return $q->whereDate('date', '<=' , $dateTo->format('Y-m-d'));
        });
        $query->when($params['walletId'] !== null, function ($q)use($params){
            return $q->where('walletId', $params['walletId']);
        });


        $transactions = $query->where('userId', $userId)->orderBy('date')->paginate($limit);
        $result = $transactions->toArray();

        if($params['groupBy'] === 'date'){
            $result = $transactions->groupBy('date')->map(function($item){
                // return $item-;
                return [
                    'date' => $item->first()->date,
                    'totalAmount' => $item->sum('amount'),
                    'transactions' => $item
                ];
    
            })->values();
            
             
        }
        
        


        return ResponseHelper::success($result);
        // return dd($arr_trasact);

        
    }

    public function getById($id){
        $transaction = Transaction::where('id', $id)->where('userId', auth()->id())->first();
        if($transaction === null) return ResponseHelper::failedNoData();
        
        if($transaction) return ResponseHelper::success($transaction);
    }
    
    public function delete($id){
        $transaction = Transaction::where('id', $id)->where('userId', auth()->id())->first();
        if($transaction === null) return ResponseHelper::failedNoData();

        $transaction->delete();
        return ResponseHelper::success($transaction, "Successfully, data has been deleted.");

    }
    public function update(Request $req, $id){
        $input = $req->all();
        $transaction = Transaction::where('id', $id)->where('userId', auth()->id())->first();
        if($transaction === null) return ResponseHelper::failedNoData();


        $validator = Validator::make($input, [
            'name'  => 'string|min:3',
            'desc'  => 'string',
            'amount'  => 'integer',
            'walletId' => 'integer',
            'categoryId' => 'integer',
            'date' => ['date_format:Y-m-d']
        ]);

        //if validation fails
        if ($validator->fails()) {
            $messages = $validator->messages();
            return ResponseHelper::failedValidation($messages->first());
        }

        $wallet = Wallet::where('id', $req->walletId)->where('userId', auth()->id())->first();
        $category = Category::where('id', $req->categoryId)->where('userId', auth()->id())->first();
        if($wallet === null && array_key_exists('walletId', $input)) return ResponseHelper::failedNoData("Wallet doesnt exist!");
        if($category === null && array_key_exists('categoryId', $input)) return ResponseHelper::failedNoData("Category doesnt exist!");

        $transaction->update([
            'name' => array_key_exists('name', $input) ? $req->name : $transaction->name,
            'desc'  => array_key_exists('desc', $input) ? $req->desc : $transaction->desc,
            'amount'  => array_key_exists('amount', $input) ? $req->amount : $transaction->amount,
            'walletId' => array_key_exists('walletId', $input) ? $req->walletId : $transaction->walletId,
            'categoryId' => array_key_exists('categoryId', $input) ? $req->categoryId : $transaction->categoryId,
            'date' => array_key_exists('date', $input) ? $req->date : $transaction->date,
        ]);
        return ResponseHelper::success($transaction, "Successfully, data has been updated.");

    }

    public function create(Request $req){
        $input = $req->all();


        $validator = Validator::make($input, [
            'name'  => 'required|string|min:3',
            'desc'  => 'string',
            'amount'  => 'integer',
            'walletId' => 'required|integer',
            'categoryId' => 'required|integer',
            'date' => ['date_format:Y-m-d']
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return ResponseHelper::failedValidation($messages->first());
        }

        $wallet = Wallet::where('id', $req->walletId)->where('userId', auth()->id())->first();
        $category = Category::where('id', $req->categoryId)->where('userId', auth()->id())->first();
        if($wallet === null) return ResponseHelper::failedNoData("Wallet id doesn't exist!");
        if($category === null) return ResponseHelper::failedNoData("Category id doesn't exist!");

        //create user
        $userId = auth()->id();
        $transaction = Transaction::create([
            'userId' => $userId,
            'name' => $req->name,
            'desc'  => $req->desc !== null ? $req->desc : "",
            'amount'  => $req->amount !== null ? $req->amount : 0,
            'walletId' => $req->walletId ,
            'categoryId' => $req->categoryId,
            'date' => $req->date,
        ]);

        //return response JSON user is created
        if($transaction) return ResponseHelper::success($transaction, "Data has been added.");



    }
}
