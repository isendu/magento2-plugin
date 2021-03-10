# Isendu Connector #

This module allows to connect the Magento orders with Isendu to manage your couriers.

## Support: 
version - 2.3.x, 2.4.x

## How to install Extension

1. Download the archive file.
2. Unzip the file
3. Create a folder [Magento_Root]/app/code/Isendu/ConnectorModule
4. Drop/move the unzipped files to directory '[Magento_Root]/app/code/Isendu/ConnectorModule'

# Enable Extension:
- php bin/magento module:enable Isendu_ConnectorModule
- php bin/magento setup:upgrade
- php bin/magento setup:di:compile
- php bin/magento cache:flush

# Disable Extension:
- php bin/magento module:disable Isendu_ConnectorModule
- php bin/magento setup:upgrade
- php bin/magento setup:di:compile
- php bin/magento cache:flush
