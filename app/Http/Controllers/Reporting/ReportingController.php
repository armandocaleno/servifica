<?php

namespace App\Http\Controllers\Reporting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Partner;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReportingController extends Controller
{    
    public function balances()
    {        
        return view('reporting.partners-balance');
    }

    public function transactions()
    {        
        return view('reporting.transactions');
    }    

    public function balances_excel(Request $request)
    {               
        $accounts = Account::all();        

        $subquery = "";
        foreach ($accounts as $account) {
            $subquery .= '(SELECT ap.value FROM account_partner ap WHERE ap.partner_id = p.id AND ap.account_id = '. $account->id .') as "cuenta' . $account->id .'" ,';
        }
        $subquery = trim($subquery, ',');
        
        $balances = DB::select('SELECT p.id, p.code, p.name, p.lastname, p.identity, '. $subquery .' FROM partners p WHERE p.status = 1 and (p.name LIKE "%'.$request->search.'%" or p.code LIKE "'.$request->search.'%" or p.identity LIKE "'.$request->search.'%")');               
        
        //instancia el archivo de excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte de saldos'); //título de página               

        //arreglo de estilos
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],            
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'alignment' => [
                'wrapText' => true
            ]
        ];

        //formato de la fila de cabecera
        $sheet->getColumnDimension('A')->setWidth(30);   
        $sheet->getStyle("A1")->applyFromArray($styleArray);  
        $sheet->getStyle("A1")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('A1', 'SOCIO'); //título de la primera celda
                
        //arreglo para las celdas 
        $cells = [0=>'B',1 =>'C', 2=> 'D', 3=> 'E', 4=> 'F', 5=> 'G', 6=> 'H', 7=> 'I', 8=> 'J', 9=> 'K', 10=> 'L', 11=> 'M', 12=> 'N', 13=> 'O', 14=> 'P', 15=> 'Q', 16=> 'R', 17=> 'S', 18=> 'T', 19=> 'U', 20=> 'V'];

        //escribe el nombre de las cuentas en la primera columna como cabecera
        foreach ($accounts as $key => $account) {     
            $sheet->getColumnDimension($cells[$key])->setWidth(18);                                  
            $sheet->getStyle($cells[$key])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($cells[$key]."1")->applyFromArray($styleArray);           
            $sheet->setCellValue($cells[$key]."1", strtoupper($account->name) );
        }

        //escribe los datos de la consulta en cada celda
        foreach ($balances as $key => $value) {            
            $sheet->setCellValue('A'.$key + 2, $value->name . " ". $value->lastname);
            foreach ($accounts as $key2 => $acc) {
                $var = "cuenta".$acc->id;
                $amount = $value->$var;
                if ($value->$var = "" || $value->$var == null) {
                    $amount = "-";
                    $sheet->getStyle($cells[$key2].$key + 2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }else {
                    $sheet->getStyle($cells[$key2].$key + 2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle($cells[$key2].$key + 2)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                }                
                $sheet->setCellValue($cells[$key2].$key + 2, $amount);
               
            }            
        }

        //nombre del archivo excel
        $file ="reporte_saldos_".date('dmYHi').".xlsx";

        //cabeceras para archivos xlsx
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$file.'"');
        header('Cache-Control: max-age=0');       

        //escribe el contenido en el archivo xlsx
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output'); //descarga el archivo
    }

    public function transactions_excel(Request $request)
    {
        $desde = Carbon::parse($request->from)->format('d/m/Y');
        $hasta = Carbon::parse($request->to)->format('d/m/Y');
        
        $partner = "Todos";
        if ($request->partner_id) {
            $p = Partner::find($request->partner_id);
            $partner = $p->name . " ". $p->lastname;
        }

        if ($request->type_id == 0) {
            $transactions = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
                                        ->select(['transactions.*', 'partners.name'])
                                        ->where('transactions.company_id', session('company')->id)        
                                        ->whereBetween('date', [$request->from, $request->to])  
                                        ->partner($request->partner_id)                      
                                        ->orderBy('date')->get();
        } else {
            $transactions = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
                                        ->select(['transactions.*', 'partners.name'])
                                        ->where('transactions.company_id', session('company')->id)        
                                        ->whereBetween('date', [$request->from, $request->to])  
                                        ->where('type', $request->type_id)
                                        ->partner($request->partner_id)                      
                                        ->orderBy('date')->get();
        }
        
        //instancia el archivo de excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte de transacciones'); //título de página               

        //arreglo de estilos
        $styleArray = [
            'font' => [
                'bold' => true,
            ],           
            'alignment' => [
                'wrapText' => true
            ],            
        ];

        $sheet->getStyle("A1")->applyFromArray($styleArray);              
        $sheet->setCellValue('A1', 'DESDE');                    
        $sheet->setCellValue('B1', $desde);

        $sheet->getStyle("A2")->applyFromArray($styleArray);              
        $sheet->setCellValue('A2', 'HASTA');                      
        $sheet->setCellValue('B2', $hasta);

        $sheet->getStyle("A3")->applyFromArray($styleArray);              
        $sheet->setCellValue('A3', 'SOCIO');                
        $sheet->setCellValue('B3', $partner);

        $sheet->getStyle("A4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getStyle('A')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('A4', 'NÚMERO');

        $sheet->getStyle("B4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getStyle('B')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('B4', 'FECHA');

        $sheet->getStyle("C4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getStyle('C')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('C4', 'TIPO');

        $sheet->getStyle("D4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getStyle('D')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('D4', 'SOCIO');

        $sheet->getStyle("E4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getStyle('E')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('E4', 'RUBROS');

        $sheet->getStyle("F4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getStyle('F')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('F4', 'REFERENCIA');

        $sheet->getStyle("G4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getStyle('G')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('G4', 'TOTAL');
        
        foreach ($transactions as $key => $value) {            
            $sheet->setCellValue('A'.$key+5, $value->number);           
                        
            $sheet->setCellValue('B'.$key+5, Carbon::parse($value->date)->format('d/m/Y'));
            if ($value->number == 1) {
                $sheet->setCellValue('C'.$key+5, 'Individual');
            }elseif ($value->number == 2) {
                $sheet->setCellValue('C'.$key+5, 'Lotes');
            } else {
                $sheet->setCellValue('C'.$key+5, 'Pago');
            }
                                    
            $sheet->setCellValue('D'.$key+5, $value->partner->name ." ". $value->partner->lastname);
            
            $sheet->getStyle('E')->getAlignment()->setWrapText(true);
            $accounts = "";
            foreach ($value->content as $account) {
                $accounts .= $account['name']."\n";
            }
            $sheet->setCellValue('E'.$key+5, trim($accounts,"\n"));
            
            $sheet->setCellValue('F'.$key+5, $value->reference);

            $sheet->getStyle('G'.$key+5)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
            $sheet->setCellValue('G'.$key+5, $value->total);
        }

         //nombre del archivo excel
         $file ="reporte_transacciones_".date('dmYHi').".xlsx";

         //cabeceras para archivos xlsx
         header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
         header('Content-Disposition: attachment;filename="'.$file.'"');
         header('Cache-Control: max-age=0');       
 
         //escribe el contenido en el archivo xlsx
         $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
         $writer->save('php://output'); //descarga el archivo
    }

    public function transactionsReporting(Request $request)
    {
        if ($request->type == "pdf") {
            $desde = Carbon::parse($request->from)->format('d/m/Y');
            $hasta = Carbon::parse($request->to)->format('d/m/Y');
            
            $partner = "Todos";
            if ($request->partner_id) {
                $p = Partner::find($request->partner_id);
                $partner = $p->name . " ". $p->lastname;
            }

            if ($request->type_id == 0) {
                $transactions = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
                                            ->select(['transactions.*', 'partners.name'])
                                            ->where('transactions.company_id', session('company')->id)       
                                            ->whereBetween('date', [$request->from, $request->to])  
                                            ->partner($request->partner_id)                      
                                            ->orderBy('date')->get();
            } else {
                $transactions = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
                                            ->select(['transactions.*', 'partners.name'])
                                            ->where('transactions.company_id', session('company')->id) 
                                            ->whereBetween('date', [$request->from, $request->to])  
                                            ->where('type', $request->type_id)
                                            ->partner($request->partner_id)                      
                                            ->orderBy('date')->get();
            }
            
            
            //genera el pdf con el contenido de la transacción
            $pdf = PDF::loadView('reporting.transactions-pdf', ['transactions' => $transactions, 'from' => $desde, 'to' => $hasta, 'partner' => $partner])->setPaper('a4', 'landscape');    
            //muestra el pdf    
            return $pdf->stream(); 
        } else {
            $this->transactions_excel($request);
        }        
    }
    
    public function balancesReporting(Request $request)
    {
        if ($request->type == "pdf") {
            $accounts = Account::all();        

            $subquery = "";
            foreach ($accounts as $account) {
                $subquery .= '(SELECT ap.value FROM account_partner ap WHERE ap.partner_id = p.id AND ap.account_id = '. $account->id .') as "cuenta' . $account->id .'" ,';
            }
            $subquery = trim($subquery, ',');
            
            $balances = DB::select('SELECT p.id, p.code, p.name, p.lastname, p.identity, '. $subquery .' FROM partners p WHERE p.company_id = '.session('company')->id.' and p.status = 1 and (p.name LIKE "%'.$request->search.'%" or p.code LIKE "'.$request->search.'%" or p.identity LIKE "'.$request->search.'%")');
            
            //genera el pdf con el contenido de la transacción
            $pdf = PDF::loadView('reporting.partners-balances-pdf', ['balances' => $balances, 'accounts' => $accounts])->setPaper('a3', 'landscape');    
            //muestra el pdf    
            return $pdf->stream();    
        } else {
            $this->balances_excel($request);
        }
        
    }

    public function partners()
    {
        return view('Reporting.partners');
    }

    public function partnersReporting(Request $request)
    {
        if ($request->type == "pdf") {
            $partners = Partner::whereStatus(Partner::ACTIVO)->whereCompanyId(session('company')->id)->get();
            //genera el pdf con el contenido de la transacción
            $pdf = PDF::loadView('reporting.partners-pdf', ['partners' => $partners]);  
            return $pdf->stream();  
        } else {
            $this->partners_excel();
        }
        
    }

    public function partners_excel()
    {
        //instancia el archivo de excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte de socios'); //título de página

        $partners = Partner::whereStatus(Partner::ACTIVO)->whereCompanyId(session('company')->id)->get();

        //arreglo de estilos
        $styleArray = [
            'font' => [
                'bold' => true                
            ]            
        ];

        $styleAlignmentArray = [                      
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ]           
        ];

        //Define el ancho de las columnas
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);

        //Aplica el arreglo de estilos al rango de celdas especificadas
        $sheet->getStyle("A1:F1")->applyFromArray($styleArray);
        $sheet->getStyle("A")->applyFromArray($styleAlignmentArray);
        $sheet->getStyle("B")->applyFromArray($styleAlignmentArray);
        $sheet->getStyle("C")->applyFromArray($styleAlignmentArray);
        $sheet->getStyle("D")->applyFromArray($styleAlignmentArray);
        $sheet->getStyle("E")->applyFromArray($styleAlignmentArray);
        $sheet->getStyle("F")->applyFromArray($styleAlignmentArray);

        //Celdas de título
        $sheet->setCellValue('A1', 'CÓDIGO');        
        $sheet->setCellValue('B1', 'IDENTIFICACIÓN');         
        $sheet->setCellValue('C1', 'NOMBRES');                 
        $sheet->setCellValue('D1', 'APELLIDOS');         
        $sheet->setCellValue('E1', 'TELÉFONO');         
        $sheet->setCellValue('F1', 'EMAIL');

        //LLena las celdas con la data
        foreach ($partners as $key => $value) {            
            $sheet->setCellValue('A'.$key+2, $value->code);                                   
            $sheet->setCellValue('B'.$key+2, $value->identity);                       
            $sheet->setCellValue('C'.$key+2, $value->name);                       
            $sheet->setCellValue('D'.$key+2, $value->lastname);                        
            $sheet->setCellValue('E'.$key+2, $value->phone);                       
            $sheet->setCellValue('F'.$key+2, $value->email);
        }

        //nombre del archivo excel
        $file ="reporte_socios_".date('dmYHi').".xlsx";

        //cabeceras para archivos xlsx
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$file.'"');
        header('Cache-Control: max-age=0');       

        //escribe el contenido en el archivo xlsx
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output'); //descarga el archivo

    }
}
