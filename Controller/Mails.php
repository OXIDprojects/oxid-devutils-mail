<?php

namespace OxidCommunity\DevutilsMails\Controller;

use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Email;
use OxidEsales\Eshop\Core\Registry;

class Mails extends FrontendController {
    protected $_sThisTemplate = 'dev_mails.tpl';

    public function init() {
        parent::init();
        if ($this->getUser()->oxuser__oxrights->value != "malladmin") {
            die("Please login with an admin user in the frontend and add something to your basket.");
        }

    }

    public function render() {
        $cfg = Registry::getConfig();
        if ($mail = $cfg->getRequestParameter("mail")) {
            $this->_preview($mail);
        }

        return parent::render();
    }

    private function _preview($mail) {
        $cfg = Registry::getConfig();
        $type = $cfg->getRequestParameter("type");

        $oUser = Registry::getSession()->getUser();
        $oEmail = oxNew(Email::class);
        $oEmail->setDebug();

        if (in_array($mail, ["sendRegisterEmail", "sendRegisterConfirmEmail", "sendNewsletterDbOptInMail"])) {
            // diese Funktionen benötigen oxUser als param
            $oEmail->$mail($oUser);
        } elseif (in_array($mail, ["sendOrderEmailToUser", "sendOrderEmailToOwner"])) {
            $oUser = $this->getUser();
            $oBasket = $this->getSession()->getBasket();
            $oOrder = oxNew(Order::class);
            $oOrder->fakeOrder($oBasket, $oUser);
            //var_dump($oOrder);
            //die();
            $oEmail->$mail($oOrder);
        } elseif ($mail == "sendSendedNowMail") {
            $oOrder = array_shift($oUser->getOrders(1)->getArray());
            $this->setAdminMode(true);
            $oEmail->$mail($oOrder);
        } elseif ($mail == "sendForgotPwdEmail") {
            // diese Funktionen benötigen eine E-Mail Adresse als param
            $oEmail->$mail($oUser->oxuser__oxusername->value);
        } elseif ($mail == "sendContactMail") {
            // diese Funktionen benötigen E-Mail Adresse, Subject und Text als params
            $sMail = $oUser->oxuser__oxusername->value;
            $sSubject = "mail subject";
            $sBody = "dear $sMail, we miss you very hard here, at OXID eShop.\n Please, don't forget us!";
            $oEmail->$mail($sMail, $sSubject, $sBody);
        } elseif ($mail == "sendSuggestMail") {
            exit;
        } else {
            exit;
        }

        if ($type == 'html') {
            echo $oEmail->getBody();
        } elseif ($type == 'plain') {
            echo "<pre>" . $oEmail->getAltBody() . "</pre>";
        } else {
            echo $oEmail->getSubject();
        }
        exit;
    }

}
