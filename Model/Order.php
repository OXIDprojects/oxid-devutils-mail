<?php

namespace OxidCommunity\DevutilsMails\Model;

use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\UtilsDate;

class Order extends Order_parent {
    /** this function simulates finalizeOrder
     *
     * @param $oBasket oxBasket
     * @param $oUser oxUser
     */
    public function fakeOrder($oBasket, $oUser) {
        $this->_setUser($oUser);
        $this->_loadFromBasket($oBasket);
        $oUserPayment = $this->_setPayment(($oBasket->getPaymentId() ? $oBasket->getPaymentId() : 'oxidpayadvance'));

        $this->oxorder__oxordernr = new Field("777");

        $sDate = date('Y-m-d H:i:s', Registry::get(UtilsDate::class)->getTime());
        $this->oxorder__oxorderdate = new Field($sDate);
        $this->oxorder__oxsenddate = new Field($sDate);

        $oBasket->setOrderId('testOrder777');

        $this->_oUser = $oUser;
        $this->_oBasket = $oBasket;
        $this->_oPayment = $oUserPayment;
    }


    public function restoreBasket() {
        $oBasket = $this->_getOrderBasket();

        $aOrderArticles = $this->getOrderArticles(true);
        if (count($aOrderArticles) > 0) {
            foreach ($aOrderArticles as $oOrderArticle) {
                $sProductID = $oOrderArticle->getProductId();
                $dAmount = $oOrderArticle->oxorderarticles__oxamount->value;
                $aSel = $oOrderArticle->getOrderArticleSelectList();
                $aPersParam = $oOrderArticle->getPersParams();

                $oBasket->addToBasket($sProductID, $dAmount, $aSel, $aPersParam);
            }
        }

        $this->_oBasket = $oBasket;

        return $oBasket;
    }

}