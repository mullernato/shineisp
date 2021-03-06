<?
class Shineisp_Api_Shineisp_Customers extends Shineisp_Api_Abstract_Action  {
    
    public function insert( $params ) {
        $this->authenticate();
        
        $form = new Api_Form_CustomerForm ( array ('action' => '#', 'method' => 'post' ) );
        
        if( array_key_exists('countrycode', $params) ) {
            $country_id     = Countries::getIDbyCode($params['countrycode']);
            if( $country_id == null ) {
                throw new Shineisp_Api_Exceptions( 400005, ":: 'countrycode' not valid" );
                exit();
            }
            
            unset($params['coutrycode']);
            $params['country_id']   = $country_id;
        }
        
        if( array_key_exists('provincecode',$params) && $params['provincecode'] != "" ) {
            $params['area'] = $params['provincecode'];
            unset($params['provincecode']);
        }
        
        if ($form->isValid ( $params ) ) {
            if( $params['status'] == false ) {
                $params['status'] = 'disabled';    
            }
            
            $idcustomers    = Customers::Create($params);
            
            $customer       = Customers::find($idcustomers);
            return $customer['uuid'];
        } else {
            $errors     = $form->getMessages();
            $message    = "";
            foreach( $errors as  $field => $errorsField ) {
                $message .= "Field '{$field}'<br/>";
                foreach( $errorsField as $error => $describe ) {
                    $message .=" => {$error} ({$describe})";
                }
            }
            
            throw new Shineisp_Api_Exceptions( 400004, ":\n{$message}" );
            exit();
        }
    }
    
}