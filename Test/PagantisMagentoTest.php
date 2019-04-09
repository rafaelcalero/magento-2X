<?php

namespace Pagantis\Pagantis\Test;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\TestCase;

/**
 * Class pagantisMagentoTest
 * @package Pagantis\Test
 */
abstract class PagantisMagentoTest extends TestCase
{
    /**
     * Magento Backoffice URL
     */
    const BACKOFFICE_FOLDER = '/admin';

    /**
     * Magento Logout URL
     */
    const LOGOUT_FOLDER = '/customer/account/logout/';

    /**
     * Magento Checkout URL
     */
    const CHECKOUT_FOLDER = '/checkout/';

    /**
     * Product name
     */
    const PRODUCT_NAME = 'Fusion Backpack';

    /**
     * Product quantity after
     */
    const PRODUCT_QTY_AFTER = 3;

    /**
     * Magento checkout Title
     */
    const CHECKOUT_TITLE = 'Checkout';

    /**
     * Magento cart Title
     */
    const CART_TITLE = 'Cart';

    /**
     * Magento success title
     */
    const SUCCESS_TITLE = 'Success Page';

    /**
     * Magento order confirmation title
     */
    const ORDER_TITLE = 'Order #';

    /**
     * Pagantis Order Title
     */
    const PAGANTIS_TITLE = 'Paga+Tarde';

    /**
     * Already processed
     */
    const NOTFOUND_TITLE = 'Merchant order not found';

    /**
     * Wrong order
     */
    const NOORDER_TITLE = 'We can not get the Pagantis identification in database';

    /**
     * Magento Logout URL
     */
    const NOTIFICATION_FOLDER = '/pagantis/notify/';

    /**
     * Magento Logout URL
     */
    const NOTIFICATION_PARAMETER = 'quoteId';

    /**
     * Magento Log URL
     */
    const LOG_FOLDER = '/pagantis/Payment/Log';

    /**
     * Magento Config URL
     */
    const CONFIG_FOLDER = '/pagantis/Payment/Config';

    /**
     * @var array
     */
    protected $configuration = array(
        'backofficeUsername' => 'admin',
        'backofficePassword' => 'password123',
        'publicKey'          => 'tk_fd53cd467ba49022e4f8215e',
        'secretKey'          => '21e57baa97459f6a',
        'methodName'         => 'Pagantis',
        'methodTitle'        => 'Financiación instantánea',
        'defaultSimulatorOpt'=> 6,
        'defaultMinIns'      => 3,
        'defaultMaxIns'      => 12,
        'minAmount'          => 70,
        'username'           => 'demo@prestashop.com',
        'password'           => 'Prestashop_demo',
        'firstname'          => 'John',
        'lastname'           => 'Döe Martinez',
        'email'              => null,
        'zip'                => '08023',
        'city'               => 'Barcelona',
        'street'             => 'Av Diagonal 585, planta 7',
        'phone'              => '600123123',
        'checkoutDescription'=> 'Paga hasta en 12'
    );

    /**
     * @var RemoteWebDriver
     */
    protected $webDriver;

    /**
     * Magento version provided for tests in commandline as an argument.
     *
     * @var String
     */
    protected $version;

    /**
     * Magento version testing url port based on magento version
     *
     * @var array
     */
    protected $versionsPort = array(
        '22' => '8085',
        '23' => '8084',
    );

    /**
     * pagantisMagentoTest constructor.
     */
    public function __construct()
    {
        if (!isset($_SERVER['argv']) ||
            !isset($_SERVER['argv'][4]) ||
            $_SERVER['argv'][4] != 'magentoVersion' ||
            !isset($_SERVER['argv'][6]) ||
            !isset($this->versionsPort[$_SERVER['argv'][6]])
        ) {
            throw new \Exception("No magentoVersion param provided or not valid for phpunit testing");
        }

        $this->version = $_SERVER['argv'][6];
        $this->configuration['magentoUrl'] = 'http://magento'.$this->version.'-test.docker:'.
            $this->versionsPort[$this->version].'/index.php';
        $this->configuration['email'] = "john.doe+".microtime(true)."@pagantis.com";

        return parent::__construct();
    }

    /**
     * Configure selenium
     */
    protected function setUp()
    {
        $this->webDriver = PagantisWebDriver::create(
            'http://localhost:4444/wd/hub',
            DesiredCapabilities::chrome(),
            180000,
            180000
        );
    }

    /**
     * @param $name
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */
    public function findByName($name)
    {
        return $this->webDriver->findElement(WebDriverBy::name($name));
    }

    /**
     * @param $id
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */
    public function findById($id)
    {
        return $this->webDriver->findElement(WebDriverBy::id($id));
    }

    /**
     * @param $className
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */
    public function findByClass($className)
    {
        return $this->webDriver->findElement(WebDriverBy::className($className));
    }

    /**
     * @param $css
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */
    public function findByCss($css)
    {
        return $this->webDriver->findElement(WebDriverBy::cssSelector($css));
    }

    /**
     * @param $link
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */
    public function findByLinkText($link)
    {
        return $this->webDriver->findElement(WebDriverBy::linkText($link));
    }

    /**
     * @param $link
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */
    public function findByPartialLinkText($link)
    {
        return $this->webDriver->findElement(WebDriverBy::partialLinkText($link));
    }

    /**
     * Quit browser
     */
    protected function quit()
    {
        $this->webDriver->quit();
    }
}