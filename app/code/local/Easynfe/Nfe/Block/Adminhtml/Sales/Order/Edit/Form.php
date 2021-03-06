<?php
/**
 * Easynfe - NF-e. 
 *
 * @title      Magento Easynfe NF-e
 * @category   General
 * @package    Easynfe_Nfe
 * @author     Indexa Development Team <desenvolvimento@indexainternet.com.br>
 * @copyright  Copyright (c) 2011 Indexa - http://www.indexainternet.com.br
 */

class Easynfe_Nfe_Block_Adminhtml_Sales_Order_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getData('action'),
            'method'    => 'post',
            'enctype'   => 'multipart/form-data'
        ));

        $nfRequest = Mage::registry('nfe_data');

        if ($nfRequest->getId()) {
            
            /**
             * get encripted request
             */
            $request = Zend_Json::decode( $nfRequest->getRequest() );
         
            $form->addField( 'id' , 'hidden', array(
                            'name' => 'id',
                            'value' => $nfRequest->getId()
                        ));
            
            /**
             * build form fieldsets
             */
            $fieldsetEmit = $form->addFieldset(
                    'nfe_emit',
                    array('legend'=>Mage::helper('nfe')->__('Grupo de identificação do emitente da NF-e'))
            );            
            $this->getFields( $fieldsetEmit, $request['nfe.NFe']['nfe.infNFe']['nfe.emit'], 'nfe.NFe[nfe.infNFe][nfe.emit]' );
            
            $fieldsetDest = $form->addFieldset(
                    'nfe_dest',
                    array('legend'=>Mage::helper('nfe')->__('Grupo de identificação do destinatário da NF-e'))
            );            
            $this->getFields( $fieldsetDest, $request['nfe.NFe']['nfe.infNFe']['nfe.dest'], 'nfe.NFe[nfe.infNFe][nfe.dest]' );
            
            if( $request['nfe.NFe']['nfe.infNFe']['nfe.det'] ){
                foreach($request['nfe.NFe']['nfe.infNFe']['nfe.det'] as $key => $items){
                     $fieldsetDet{$key} = $form->addFieldset(
                        'nfe_det'.$key,
                        array('legend'=>Mage::helper('nfe')->__('Item -' . $items['@nItem']))
                    );
                     $this->getFields( $fieldsetDet{$key}, $request['nfe.NFe']['nfe.infNFe']['nfe.det'][$key], 'nfe.NFe[nfe.infNFe][nfe.det]['. $key . ']' );
                }
            }
            
            $fieldsetTotal = $form->addFieldset(
                    'nfe_total',
                    array('legend'=>Mage::helper('nfe')->__('Grupo de Valores Totais da NF-e'))
            );            
            $this->getFields( $fieldsetTotal, $request['nfe.NFe']['nfe.infNFe']['nfe.total'], 'nfe.NFe[nfe.infNFe][nfe.total]' );
            
            $fieldsetComp = $form->addFieldset(
                    'nfe_complemento',
                    array('legend'=>Mage::helper('nfe')->__('Informações Adicionais'))
            );
            
            $fieldsetComp->addField( 'nfe.NFe[nfe.infNFe][nfe.infAdic][nfe.infCpl]', 'textarea', array(
                        'name' => 'nfe.NFe[nfe.infNFe][nfe.infAdic][nfe.infCpl]',
                        'value' => $request['nfe.NFe']['nfe.infNFe']['nfe.infAdic']['nfe.infCpl'],
                        'label' => Mage::helper('nfe')->__( '[nfe.infAdic][nfe.infCpl]' )
                    ));
        }

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
    
    /**
     * create form elements 
     * 
     * @param type $form
     * @param type $elements
     * @param type $prefix
     * @param type $name 
     */
    private function getFields( $form, $elements, $prefix,  $name = null ){
        
        if( is_array( $elements ) ){
            foreach( $elements as $key => $element ){
                if( is_array ($element) ){
                    $this->getFields( $form, $elements[$key],$prefix,  $name . '['.$key.']' );
                }else{
                    $form->addField( str_replace( array('[', ']', '@'), array('', '', '-'), $prefix.$name.$key) , 'text', array(
                        'name' => $prefix . $name.'['.$key.']',
                        'value' => $element,
                        'label' => Mage::helper('nfe')->__( trim( $name.'['.$key.']') )
                    ));
                }
            }
        }  
        
        
    }
}
