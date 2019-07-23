<?php

namespace OxidCommunity\DevutilsMails\Core;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\SeoEncoder;

class Email extends Email_parent {
    private $_blDebug = false;

    public function setDebug($blDebug = true) {
        $this->_blDebug = $blDebug;
    }

    public function isDebug() {
        return $this->_blDebug;
    }

    public function send() {
        if ($this->isDebug()) {
            $encoder = Registry::get(SeoEncoder::class);
            $sFile = $this->getSubject();
            $sFile = $encoder->encodeString($sFile, true, 0);
            $sFile = preg_replace("/[^A-Za-z0-9" . preg_quote('-', '/') . " \t\/]+/", '', $sFile);
            $sFile = preg_replace("/[^A-Za-z0-9" . preg_quote('-', '/') . "\/]+/", '_', $sFile);
            if (is_file(Registry::getConfig()->getLogsDir() . $sFile . '.html')) {
                unlink(Registry::getConfig()->getLogsDir() . $sFile . '.html');
            }

            Registry::getUtils()->writeToLog(preg_replace("/\r|\n/", "", $this->getBody()), $sFile . '.html');
            return $this;
        }
        return parent::send();
    }

    public function sendForgotPwdEmail($sEmailAddress, $sSubject = null) {
        Registry::getUtils()->writeToLog($sEmailAddress . " haz forgot pwd!", 'pwd.log');
        $ret = parent::sendForgotPwdEmail($sEmailAddress, $sSubject);
        Registry::getUtils()->writeToLog($sEmailAddress . " haz forgot pwd!!!!", 'pwd.log');
        return ($this->isDebug()) ? $this : $ret;
    }
}
