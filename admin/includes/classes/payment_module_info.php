<?php
  class paymentModuleInfo {

    public $payment_code;
    public $keys = [];

    public function __construct($pmKeys) {
      $this->paymentModuleInfo($pmKeys);
    }

    public function paymentModuleInfo($pmKeys) {
      $this->payment_code = $pmKeys['payment_code'];

      foreach ($pmKeys as $configuration_key) {
        $this->keys[$configuration_key] = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT configuration_title AS title, configuration_value AS value, configuration_description AS description
 FROM configuration
 WHERE configuration_key = '%s'
EOSQL
          , $GLOBALS['db']->escape($configuration_key)))->fetch_assoc();
      }
    }

  }
