<?php
class Payment {
    public function salaryAndBonusPayment(){
           header('Content-Type: text/csv; charset=utf-8');
		   header('Content-Disposition: attachment; filename="payments.csv"');
           $handle = fopen('php://output', 'w');
         
          $date = new DateTime();

        //loop on the next 12 months
        for ($month = 1; $month <= 12; $month++){
            $nameOfMonth = $date->format('F'); //Name of the Month
            //set the date to the last day of the month
            $date->setDate($date->format('Y'), $date->format('m'), $date->format('t'));
            //Get the correct payment date for the salary
            $salaryPaymentDate = $this->getPaymentDate($date);
            
            $date->setDate($date->format('Y'), $date->format('m'), 15);
            $date->modify( '+1 month' ); //advance one month (the bonus paid on the next month)
          
            //Get the correct payment date for the bonus
            $payBonus = true;
            $bonusPaymentDate = $this->getPaymentDate($date, $payBonus);
                
			//insert the requested data into a csv file			
            fputcsv($handle, array($nameOfMonth, $salaryPaymentDate, $bonusPaymentDate ));         
        }
        fclose($handle);
    }
    public function getPaymentDate($date, $payBonus = false){
        $NumOfDay = $date->format('N'); //day of the week 1 for Monday - 7 for Sunday
        
        SWITCH($NumOfDay){
            CASE 5: //if its friday
                 ////if its salary back 1 day (to Thursday), if its bonus forward 5 days (to Wednesday)
                 $payBonus ? $date->add(new DateInterval('P5D')) : $date->sub(new DateInterval('P1D'));
                break;
            CASE 6://if its saturday 
                 //if its salary back 2 days (to Thursday), if its bonus forward 4 days (to Wednesday)
                 $payBonus ? $date->add(new DateInterval('P4D')) : $date->sub(new DateInterval('P2D'));
                break;
            DEFAULT:
        }
        $paymentDate = $date->format('d-m-Y');
        return $paymentDate;
    }

}
 $payment = new Payment();
 $payment->salaryAndBonusPayment();
 ?>
