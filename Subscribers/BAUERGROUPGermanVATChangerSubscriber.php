<?php

namespace BAUERGROUPGermanVATChanger\Subscribers;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\Plugin\CachedConfigReader;

class BAUERGROUPGermanVATChangerSubscriber implements SubscriberInterface
{
   private $config;

   public function __construct(CachedConfigReader $configReader, $pluginName)
   {
       $this->config = $configReader->getByPluginName($pluginName);       
   }

   public static function getSubscribedEvents()
   {		
       return [			
           'Shopware_CronJob_BAUERGROUPHandleVATChange' => 'onCronjobExecute'
       ];
   }

   public function onCronjobExecute(\Shopware_Components_Cron_CronJob $job)
   {
		//Shopware()->Pluginlogger()->info('BAUERGROUPGermanVATChanger: START OF CRON JOB');

		//Configuration
		$keepGrossPrice = $this->config["KeepGrossPrice"];
		$dateVATLowering = new \DateTime($this->config["DateVATLowering"]);
		$dateVATIncrease = new \DateTime($this->config["DateVATIncrease"]);

		//Current Time
		$dateNow = new \DateTime();

		//True if within low VAT timeframes
		$lowVATTimeframe = ($dateVATLowering <= $dateNow) && ($dateVATIncrease >= $dateNow);

		//Create Rates
		$this->createVATRates($lowVATTimeframe);

		//Change Items
		$this->changeItems($lowVATTimeframe, $keepGrossPrice);
		
		//Shopware()->Pluginlogger()->info('BAUERGROUPGermanVATChanger: END OF CRON JOB');
   }

   private function createVATRates($hasLowVATRate)
   {
	   $sqlVATRates = "";
	   
	   if ($hasLowVATRate)
	   {
		   //Create 16+5% VAT rates if not exists already
		   $sqlVATRates = "INSERT INTO `s_core_tax` (`tax`, `description`)
							SELECT * FROM (SELECT 16.00, '16%') AS tempTable
							WHERE NOT EXISTS (
								SELECT `tax` FROM `s_core_tax` WHERE `tax` = 16.00
							) LIMIT 1;

							INSERT INTO `s_core_tax` (`tax`, `description`)
							SELECT * FROM (SELECT 5.00, '5%') AS tempTable
							WHERE NOT EXISTS (
								SELECT `tax` FROM `s_core_tax` WHERE `tax` = 5.00
							) LIMIT 1;
						   ";
	   }
	   else 
	   {
		   //Create 19+7% VAT rates if not exists already
		   $sqlVATRates = "INSERT INTO `s_core_tax` (`tax`, `description`)
							SELECT * FROM (SELECT 19.00, '19%') AS tempTable
							WHERE NOT EXISTS (
								SELECT `tax` FROM `s_core_tax` WHERE `tax` = 19.00
							) LIMIT 1;

							INSERT INTO `s_core_tax` (`tax`, `description`)
							SELECT * FROM (SELECT 7.00, '7%') AS tempTable
							WHERE NOT EXISTS (
								SELECT `tax` FROM `s_core_tax` WHERE `tax` = 7.00
							) LIMIT 1;
						   ";
	   }
	  
	   //Apply by SQL command
	   Shopware()->Db()->exec($sqlVATRates);
   }
   
   private function getVATIDs()
   {	   
	    $queryBuilder = Shopware()->Container()->get('dbal_connection')->createQueryBuilder();
	   
	    $data = $queryBuilder->select('id', 'tax')
				->from('s_core_tax')
				->execute()
				->fetchAll();

		return $data;
   }
   
   private function getVATIDforRate($vatRate)
   {	   
	    $queryBuilder = Shopware()->Container()->get('dbal_connection')->createQueryBuilder();
	   
	    $data = $queryBuilder->select('id')
				->from('s_core_tax')
				->where('tax = :vat')
				->setParameter('vat', $vatRate)
				->setMaxResults(1)	
				->execute()
				->fetchAll(\PDO::FETCH_COLUMN);

		return $data[0];
   }
   
   private function updateProductVATs($oldTaxID, $newTaxID)
   {
	   if (is_null($oldTaxID) || is_null($newTaxID))
		   return;
	   
	   Shopware()->Db()->exec("UPDATE `s_articles` SET `taxID` = " . $newTaxID . " WHERE `taxID` = " . $oldTaxID . ";");
   }
   
   private function updateProductPrices($taxID, $oldTax, $newTax)
   {
	   if (is_null($taxID))
		   return;
	   
	   Shopware()->Db()->exec("UPDATE `s_articles_prices`, `s_articles` SET `s_articles_prices`.`price` = `s_articles_prices`.`price` / " . $newTax . " * ". $oldTax . " WHERE (`s_articles`.id = `s_articles_prices`.`articleID` AND `s_articles`.`taxID` = " . $taxID . ");");
   }
   
   private function changeItems($hasLowVATRate, $keepGrossPrice)
   {
		$taxID19 = $this->getVATIDforRate(19.00);
		$taxID7 = $this->getVATIDforRate(7.00);

		$taxID16 = $this->getVATIDforRate(16.00);
		$taxID5 = $this->getVATIDforRate(5.00);
		
		//Products price update
		if ($keepGrossPrice)
		{
			if ($hasLowVATRate)
			{
				//19->16 + 7->5% VAT
				$this->updateProductPrices($taxID19, 1.19, 1.16);
				$this->updateProductPrices($taxID7, 1.07, 1.05);
			}
			else
			{
				//16->19 + 5->7% VAT
				$this->updateProductPrices($taxID16, 1.16, 1.19);
				$this->updateProductPrices($taxID5, 1.05, 1.07);
			}
		}
		
		//Pseudoprices
		//UPDATE `s_articles_prices`, `s_articles` SET `s_articles_prices`.`pseudoprice` = `s_articles_prices`.`pseudoprice` / 1.16 * 1.19 WHERE (`s_articles`.id = `s_articles_prices`.`articleID` AND `s_articles_prices`.`pseudoprice` > 0 AND `s_articles`.`taxID` = 5);
		
		//Products VAT update
		if ($hasLowVATRate)
		{
			//19->16 + 7->5% VAT
			$this->updateProductVATs($taxID19, $taxID16);
			$this->updateProductVATs($taxID7, $taxID5);
		}
		else
		{
			//16->19 + 5->7% VAT
			$this->updateProductVATs($taxID16, $taxID19);
			$this->updateProductVATs($taxID5, $taxID7);
		}
		
		//Update Other VAT rates
		if ($hasLowVATRate)
		{
			//19->16% VAT
			Shopware()->Db()->exec("INSERT IGNORE INTO `s_core_config_values` (`element_id`, `shop_id`, `value`) VALUES (186, 1, 's:2:\"16\";');
									INSERT IGNORE INTO `s_core_config_values` (`element_id`, `shop_id`, `value`) VALUES (188, 1, 's:2:\"16\";');
							   ");
		}
		else
		{
			//16->19% VAT
			Shopware()->Db()->exec("DELETE FROM `s_core_config_values` WHERE `element_id` IN (186, 188)");
		}
   }
}

?>
