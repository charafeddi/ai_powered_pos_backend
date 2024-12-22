<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Carbon\Carbon;
use DB;

class AnalyticsController extends Controller
{
    public function getEarnings(Request $request, $User_id)
    {
        $period = $request->query('period', 'day'); // default to 'day' if no period is specified

        switch ($period) {
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                break;
            case 'day':
            default:
                $startDate = Carbon::today();
        }

        $earnings = Sale::where('user_id', $User_id)->where(function($query) use ($startDate) {
             $query->whereBetween('created_at', [$startDate, Carbon::now()])
                   ->orWhereBetween('updated_at', [$startDate, Carbon::now()]);
        })->sum('amount_paid');

        return response()->json([
            'earnings' => number_format($earnings, 2)
        ]);
    }

    public function getNetProfit(Request $request, $User_id)
    {
        $period = $request->query('period', 'day'); // period can be 'day', '3months', '6months', '1year'

        switch ($period) {
            case '3months':
                $startDate = Carbon::now()->subMonths(3);
                break;
            case '6months':
                $startDate = Carbon::now()->subMonths(6);
                break;
            case '1year':
                $startDate = Carbon::now()->subYear();
                break;
            case 'day':
            default:
                $startDate = Carbon::today();
        }

        $netProfit = Sale::where('sales.user_id', $User_id)->where('sales.created_at', '>=', $startDate)
            ->select(DB::raw('ROUND(sum((sale_items.unit_price - products.prix_achat) * sale_items.quantity),3) as net_profit'))
            ->join('sale_items', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->value('net_profit');

        return response()->json([
            'net_profit' => number_format($netProfit,2)
        ]);
    }

    public function getPopularProducts(Request $request, $User_id)
    {
        $period = $request->query('period', '7months'); // period can be '3months'

        switch ($period) {
            case '7months':
                $startDate = Carbon::now()->subMonths(7);
                break;
            default:
                $startDate = Carbon::now()->subMonths(3);
        }

        $popularProducts = Sale::where('sales.user_id', $User_id)->where('sales.created_at', '>=', $startDate)
            ->select('products.designation', DB::raw('sum(sale_items.quantity) as total_quantity'))
            ->join('sale_items', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->groupBy('products.designation')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        return response()->json($popularProducts);
    }


    public function getTop10PopularProducts(Request $request, $User_id)
    {
        // Join the sales_items table with the products table
        $top10PopularProducts = Sale::where('sales.user_id', $User_id)
            ->select('products.product_code', DB::raw('CAST(SUM(sale_items.quantity) AS UNSIGNED) as total_quantity'))
            ->join('sale_items', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->groupBy('products.product_code')
            ->orderByDesc('total_quantity')
            ->take(10) // Limit the result to 10
            ->get();
    
        return response()->json($top10PopularProducts);
    }
    

    public function getInventoryLevels($User_id)
    {
        $inventoryLevels = Product::where('user_id', $User_id)->select('designation', 'quantity')
            ->get();

        return response()->json($inventoryLevels);
    }

    public function getTotalSales(Request $request ,$User_id)
    {
        $period = $request->query('period', 'day'); // default to 'day' if no period is specified

        switch ($period) {
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                break;
            case 'day':
            default:
                $startDate = Carbon::today();
        }

        $totalSales = Sale::where('user_id', $User_id)->where('created_at', '>=', $startDate)->sum('total_amount');

        return response()->json([
            'total_sales' => number_format($totalSales,2)
        ]);
    }

    public function getSupplierPerformance($User_id)
    {
        $supplierPerformance = Product::where('suppliers.user_id', $User_id)
            ->select('suppliers.id', 'suppliers.name', DB::raw('ROUND(sum((products.quantity * products.prix_achat) - (products.quantity * products.prix_achat * products.discount/100)),2) as total_cost'))
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->groupBy('suppliers.id', 'suppliers.name')
            ->orderBy('total_cost', 'desc')
            ->get();

        return response()->json($supplierPerformance);
    }

    public function getLowInStockProduct($User_id){
        $lowInStockProduct = Product::where('user_id', $User_id)
        ->where('quantity', '<=' ,10)
        ->get();

        return response()->json($lowInStockProduct);
    }

    public function getSalesTrends(Request $request, $User_id)
    {
        $period = $request->query('period', 'day');

        $salesQuery = Sale::where('user_id', $User_id)
        ->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(amount_paid) as total_sales')
        )
        ->groupBy('date')
        ->orderBy('date', 'asc');

        if ($period === 'day') {
            $salesQuery->whereDate('created_at', Carbon::today());
        } elseif ($period === 'week') {
            $salesQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $salesQuery->whereMonth('created_at', Carbon::now()->month);
        } elseif ($period === 'year') {
            $salesQuery->whereYear('created_at', Carbon::now()->year);
        }
              
        $salesTrends = $salesQuery->get();

        return response()->json($salesTrends);
    }

    public function getPeakSalesHours(Request $request, $User_id)
    {
        $period = $request->query('period', 'day');
        
        $salesQuery = Sale::where('user_id', $User_id)
            ->select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COALESCE(SUM(total_amount), 0) as total_sales')
            )
            ->groupBy('hour')
            ->orderBy('hour', 'asc');

        if ($period === 'day') {
            $salesQuery->whereDate('created_at', Carbon::today());
        } elseif ($period === 'week') {
            $salesQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $salesQuery->whereMonth('created_at', Carbon::now()->month);
        } elseif ($period === 'year') {
            $salesQuery->whereYear('created_at', Carbon::now()->year);
        }

        $salesByHours = $salesQuery->get();

        return response()->json($salesByHours);
    }

}