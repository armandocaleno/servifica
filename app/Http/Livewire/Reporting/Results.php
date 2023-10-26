<?php

namespace App\Http\Livewire\Reporting;

use App\Models\Accounting;
use Illuminate\Support\Facades\DB;
use PDF;
use Livewire\Component;

class Results extends Component
{
    public $level_global = 4;
    public $from, $to;

    public function render()
    {
        $ingresos = $this->ingresos($this->from, $this->to);
        $otros_ingresos = $this->otros_ingresos($this->from, $this->to);
        $costos = $this->costos($this->from, $this->to);
        $gastos = $this->gastos($this->from, $this->to);
        $impuestos = $this->impuestos($this->from, $this->to);
    
        return view('livewire.reporting.results', compact('ingresos', 'otros_ingresos', 'costos', 'gastos', 'impuestos'));
    }

     // CALCULA LOS INGRESOS
     function ingresos($from, $to){
        
        // $cuentas_ingresos = Accounting::where('account_class_id', '4')
        //                             ->where('group', '1')->get();

        $accountings = DB::table('journal_details')
                    ->join('accountings', 'journal_details.accounting_id', '=', 'accountings.id')
                    ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
                    ->selectRaw('accountings.id, accountings.parent_id, accountings.level, accountings.code, accountings.name, accountings.account_class_id, accountings.account_type_id, sum(debit_value) as total_debe, sum(credit_value) as total_haber')
                    ->groupBy('accountings.id', 'accountings.parent_id', 'accountings.level','accountings.code', 'accountings.name', 'accountings.account_class_id', 'accountings.account_type_id')
                    ->where('accountings.company_id', '=', session('company')->id)
                    ->where('journals.state', '=', 1)
                    ->whereBetween('journals.date', [$from, $to])
                    ->where('accountings.account_class_id', '=', '4')
                    ->get();
        
        $acc = [];
        $ingresos = [];
        $acc['total'] = 0;
        
        // AGREGA LAS CUENTAS DE DETALLE
        foreach ($accountings as $value) {
            $acc['id'] = $value->id;
            $acc['codigo'] = $value->code;
            $acc['cuenta'] = $value->name;
            $acc['padre'] = $value->parent_id;
            $acc['nivel'] = $value->level;
        
            // OPERACION DEPENDIENDO LA NATURALEZA DE LA CUENTA (DEUDORA O ACREEDORA)
            if ($value->account_type_id == 1) {
                $acc['total'] = floatval($value->total_debe) - floatval($value->total_haber);
            }else {
                $acc['total'] = floatval($value->total_haber) - floatval($value->total_debe);
            }

            if ($acc['total'] != 0) {
                $ingresos[] = $acc;
            }
        }

        return $ingresos;
    }

     // CALCULA OTROS INGRESOS
    function otros_ingresos($from, $to){
        
        // $cuentas_otros_ingresos = Accounting::where('account_class_id', '5')
        //                             ->where('group', '1')->get();

        $accountings = DB::table('journal_details')
                    ->join('accountings', 'journal_details.accounting_id', '=', 'accountings.id')
                    ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
                    ->selectRaw('accountings.id, accountings.parent_id, accountings.level, accountings.code, accountings.name, accountings.account_class_id, accountings.account_type_id, sum(debit_value) as total_debe, sum(credit_value) as total_haber')
                    ->groupBy('accountings.id', 'accountings.parent_id', 'accountings.level','accountings.code', 'accountings.name', 'accountings.account_class_id', 'accountings.account_type_id')
                    ->where('accountings.company_id', '=', session('company')->id)
                    ->where('journals.state', '=', 1)
                    ->whereBetween('journals.date', [$from, $to])
                    ->where('accountings.account_class_id', '=', '5')
                    ->get();
        
        $acc = [];
        $ingresos = [];
        $acc['total'] = 0;
        
        // AGREGA LAS CUENTAS DE DETALLE
        foreach ($accountings as $value) {
            $acc['id'] = $value->id;
            $acc['codigo'] = $value->code;
            $acc['cuenta'] = $value->name;
            $acc['padre'] = $value->parent_id;
            $acc['nivel'] = $value->level;
        
            // OPERACION DEPENDIENDO LA NATURALEZA DE LA CUENTA (DEUDORA O ACREEDORA)
            if ($value->account_type_id == 1) {
                $acc['total'] = floatval($value->total_debe) - floatval($value->total_haber);
            }else {
                $acc['total'] = floatval($value->total_haber) - floatval($value->total_debe);
            }

            if ($acc['total'] != 0) {
                $ingresos[] = $acc;
            }
        }

        return $ingresos;
    }

    // CALCULA LOS COSTOS DE VENTA
    function costos($from, $to){
        
        $cuentas_costo_ventas = Accounting::where('account_class_id', '6')
                                    ->where('group', '1')->get();

        $accountings = DB::table('journal_details')
                    ->join('accountings', 'journal_details.accounting_id', '=', 'accountings.id')
                    ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
                    ->selectRaw('accountings.id, accountings.parent_id, accountings.level, accountings.code, accountings.name, accountings.account_class_id, accountings.account_type_id, sum(debit_value) as total_debe, sum(credit_value) as total_haber')
                    ->groupBy('accountings.id', 'accountings.parent_id', 'accountings.level','accountings.code', 'accountings.name', 'accountings.account_class_id', 'accountings.account_type_id')
                    ->where('accountings.company_id', '=', session('company')->id)
                    ->whereBetween('journals.date', [$from, $to])
                    ->where('accountings.account_class_id', '=', '6')
                    ->get();
        
        $acc = [];
        $costos = [];
        $acc['total'] = 0;
        
        // AGREGA LAS CUENTAS DE DETALLE
        foreach ($accountings as $value) {
            $acc['id'] = $value->id;
            $acc['codigo'] = $value->code;
            $acc['cuenta'] = $value->name;
            $acc['padre'] = $value->parent_id;
            $acc['nivel'] = $value->level;
        
            // OPERACION DEPENDIENDO LA NATURALEZA DE LA CUENTA (DEUDORA O ACREEDORA)
            if ($value->account_type_id == 1) {
                $acc['total'] = floatval($value->total_debe) - floatval($value->total_haber);
            }else {
                $acc['total'] = floatval($value->total_haber) - floatval($value->total_debe);
            }

            if ($acc['total'] != 0) {
                $costos[] = $acc;
            }
        }

        return $costos;
    }

    // CALCULA LOS GASTOS
    function gastos($from, $to){
        
        $cuentas_gastos = Accounting::where('account_class_id', '7')
                                    ->where('group', '1')->get();

        $accountings = DB::table('journal_details')
                    ->join('accountings', 'journal_details.accounting_id', '=', 'accountings.id')
                    ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
                    ->selectRaw('accountings.id, accountings.parent_id, accountings.level, accountings.code, accountings.name, accountings.account_class_id, accountings.account_subclass_id, accountings.account_type_id, sum(debit_value) as total_debe, sum(credit_value) as total_haber')
                    ->groupBy('accountings.id', 'accountings.parent_id', 'accountings.level','accountings.code', 'accountings.name', 'accountings.account_class_id', 'accountings.account_subclass_id', 'accountings.account_type_id')
                    ->where('accountings.company_id', '=', session('company')->id)
                    ->whereBetween('journals.date', [$from, $to])
                    ->where('accountings.account_class_id', '=', '7')
                    ->where('accountings.account_subclass_id', '=', null)
                    ->get();
        
        $acc = [];
        $gastos = [];
        $acc['total'] = 0;
        // dd($accountings);
        // AGREGA LAS CUENTAS DE DETALLE
        foreach ($accountings as $value) {
            $acc['id'] = $value->id;
            $acc['codigo'] = $value->code;
            $acc['cuenta'] = $value->name;
            $acc['padre'] = $value->parent_id;
            $acc['nivel'] = $value->level;
        
            // OPERACION DEPENDIENDO LA NATURALEZA DE LA CUENTA (DEUDORA O ACREEDORA)
            if ($value->account_type_id == 1) {
                $acc['total'] = floatval($value->total_debe) - floatval($value->total_haber);
            }else {
                $acc['total'] = floatval($value->total_haber) - floatval($value->total_debe);
            }

            if ($acc['total'] != 0) {
                $gastos[] = $acc;
            }
        }

      
        // CALCULA LOS TOTALES DE LAS CUENTAS DE GRUPO
        // for ($i = $this->level_global - 1; $i >= 1; $i--) { 
        //     foreach ($cuentas_gastos as $value) {
        //         if ($value->level == $i)  {
        //             $acc['id'] = $value->id;
        //             $acc['codigo'] = $value->code;
        //             $acc['cuenta'] = $value->name;
        //             $acc['padre'] = $value->parent_id;
        //             $acc['nivel'] = $value->level;
        //             $acc['total'] = 0;
    
        //             foreach ($gastos as $v) {
        //                 if ($v['padre'] == $value->id) {
        //                     $acc['total'] += $v['total'];
        //                 }
        //             }

        //             if ($acc['total'] != 0) {
        //                 $gastos[] = $acc;
        //             }
                    
        //         }
        //     }
        // }

        // $gastos = $this->sort($gastos);
        return $gastos;
    }

    // CALCULA LOS IMPUESTOS
    function impuestos($from, $to){
        
        $cuentas_impuestos = Accounting::where('account_class_id', '7')
                                    ->where('group', '1')->get();

        // $accountings = DB::table('journal_details')
        //             ->join('accountings', 'journal_details.accounting_id', '=', 'accountings.id')
        //             ->join('account_classes', 'account_classes.id', '=', 'accountings.account_class_id')
        //             ->join('account_subclasses', 'account_classes.id', '=', 'account_subclasses.account_class_id')
        //             ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
        //             ->selectRaw('accountings.id, accountings.parent_id, accountings.level, accountings.code, accountings.name, accountings.account_class_id, accountings.account_type_id, account_subclasses.id, sum(debit_value) as total_debe, sum(credit_value) as total_haber')
        //             ->groupBy('accountings.id', 'accountings.parent_id', 'accountings.level','accountings.code', 'accountings.name', 'accountings.account_class_id', 'accountings.account_type_id', 'account_subclasses.id')
        //             ->where('accountings.company_id', '=', session('company')->id)
        //             ->whereBetween('journals.date', [$from, $to])
        //             // ->where('accountings.account_class_id', '=', '7')
        //             ->where('account_subclasses.id', '=', '4')
        //             ->get();
        $accountings = DB::table('journal_details')
                    ->join('accountings', 'journal_details.accounting_id', '=', 'accountings.id')
                    ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
                    ->selectRaw('accountings.id, accountings.parent_id, accountings.level, accountings.code, accountings.name, accountings.account_class_id, accountings.account_type_id, accountings.account_subclass_id, sum(debit_value) as total_debe, sum(credit_value) as total_haber')
                    ->groupBy('accountings.id', 'accountings.parent_id', 'accountings.level','accountings.code', 'accountings.name', 'accountings.account_class_id', 'accountings.account_type_id', 'accountings.account_subclass_id')
                    ->where('accountings.company_id', '=', session('company')->id)
                    ->whereBetween('journals.date', [$from, $to])
                    ->where('accountings.account_subclass_id', '=', '4')
                    ->get();
   
        $acc = [];
        $impuestos = [];
        $acc['total'] = 0;
        
        // AGREGA LAS CUENTAS DE DETALLE
        foreach ($accountings as $value) {
            $acc['id'] = $value->id;
            $acc['codigo'] = $value->code;
            $acc['cuenta'] = $value->name;
            $acc['padre'] = $value->parent_id;
            $acc['nivel'] = $value->level;
        
            // OPERACION DEPENDIENDO LA NATURALEZA DE LA CUENTA (DEUDORA O ACREEDORA)
            if ($value->account_type_id == 1) {
                $acc['total'] = floatval($value->total_debe) - floatval($value->total_haber);
            }else {
                $acc['total'] = floatval($value->total_haber) - floatval($value->total_debe);
            }

            if ($acc['total'] != 0) {
                $impuestos[] = $acc;
            }
        }

      
        // CALCULA LOS TOTALES DE LAS CUENTAS DE GRUPO
        // for ($i = $this->level_global - 1; $i >= 1; $i--) { 
        //     foreach ($cuentas_impuestos as $value) {
        //         if ($value->level == $i)  {
        //             $acc['id'] = $value->id;
        //             $acc['codigo'] = $value->code;
        //             $acc['cuenta'] = $value->name;
        //             $acc['padre'] = $value->parent_id;
        //             $acc['nivel'] = $value->level;
        //             $acc['total'] = 0;
    
        //             foreach ($impuestos as $v) {
        //                 if ($v['padre'] == $value->id) {
        //                     $acc['total'] += $v['total'];
        //                 }
        //             }

        //             if ($acc['total'] != 0) {
        //                 $impuestos[] = $acc;
        //             }
                    
        //         }
        //     }
        // }
        // $impuestos = $this->sort($impuestos);
        return $impuestos;
    }

    // ORDENA LOS ARREGLOS DE LAS CUENTAS
    function sort(array $arr) {
         $sorted_array = [];

         foreach ($arr as $a) {
            if ($a['nivel'] == 1) {
                $ac['id'] = $a['id'];
                $ac['codigo'] = $a['codigo'];
                $ac['cuenta'] = $a['cuenta'];
                $ac['padre'] = $a['padre'];
                $ac['nivel'] = $a['nivel'];
                $ac['total'] = $a['total'];
                $sorted_array[] = $ac;

                $ac = [];
                foreach ($arr as $b) {
                    if ($b['nivel'] == 2 && $b['padre'] == $a['id']) {
                        $ac['id'] = $b['id'];
                        $ac['codigo'] = $b['codigo'];
                        $ac['cuenta'] = $b['cuenta'];
                        $ac['padre'] = $b['padre'];
                        $ac['nivel'] = $b['nivel'];
                        $ac['total'] = $b['total'];

                        $sorted_array[] = $ac;
                        
                        $ac = [];
                        foreach ($arr as $c) {
                            if ($c['nivel'] == 3 && $c['padre'] == $b['id']) {
                                $ac['id'] = $c['id'];
                                $ac['codigo'] = $c['codigo'];
                                $ac['cuenta'] = $c['cuenta'];
                                $ac['padre'] = $c['padre'];
                                $ac['nivel'] = $c['nivel'];
                                $ac['total'] = $c['total'];
                                $sorted_array[] = $ac;
                
                                foreach ($arr as $d) {
                                    if ($d['nivel'] == 4 && $d['padre'] == $c['id']) {
                                        $ac['id'] = $d['id'];
                                        $ac['codigo'] = $d['codigo'];
                                        $ac['cuenta'] = $d['cuenta'];
                                        $ac['padre'] = $d['padre'];
                                        $ac['nivel'] = $d['nivel'];
                                        $ac['total'] = $d['total'];
                
                                        $sorted_array[] = $ac;
                                        $ac = [];
                                    }

                                    if ($this->level_global > 4) {
                                        foreach ($arr as $e) {
                                            if ($e['nivel'] == 5 && $e['padre'] == $d['id']) {
                                                $ac['id'] = $e['id'];
                                                $ac['codigo'] = $e['codigo'];
                                                $ac['cuenta'] = $e['cuenta'];
                                                $ac['padre'] = $e['padre'];
                                                $ac['nivel'] = $e['nivel'];
                                                $ac['total'] = $e['total'];
                        
                                                $sorted_array[] = $ac;
                                                $ac = [];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $sorted_array;
    }
}
