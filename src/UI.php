<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use AppUtils\FileHelper;
use AppUtils\Interface_Stringable;
use AppUtils\OutputBuffering;
use AppUtils\Request;
use Mistralys\FELHQuestEditor\UI\Button;
use Mistralys\FELHQuestEditor\UI\Icon;
use Mistralys\FELHQuestEditor\UI\Message;
use testsuites\ConvertHelper\WordSplitterTest;

class UI
{
    public const REQUEST_VAR_PAGE = 'page';
    private const SESSION_VAR_PREFIX = 'felhqm_';
    private const SESSION_VAR_MESSAGES = self::SESSION_VAR_PREFIX.'messages';

    private static bool $showXMLTags = false;

    /**
     * @var string[]
     */
    private static array $jsOnload = array();

    /**
     * @var string[]
     */
    private static array $jsHead = array();

    public static function icon() : Icon
    {
        return new Icon('');
    }

    /**
     * @param string|number|Interface_Stringable|NULL $label
     * @return Button
     */
    public static function button($label) : Button
    {
        return new Button($label);
    }

    /**
     * @param string|number|Interface_Stringable|NULL $message
     * @return Message
     */
    public static function message($message) : Message
    {
        return new Message($message);
    }

    public static function setShowXMLTags(bool $enabled) : void
    {
        self::$showXMLTags = $enabled;
    }

    public static function isShowXMLTagsEnabled() : bool
    {
        return self::$showXMLTags;
    }

    public static function addJSHead(string $statement) : void
    {
        if(!in_array($statement, self::$jsHead)) {
            self::$jsHead[] = rtrim($statement, ';');
        }
    }

    public static function addJSOnload(string $statement) : void
    {
        if(!in_array($statement, self::$jsOnload)) {
            self::$jsOnload[] = rtrim($statement, ';');
        }
    }

    public static function getJSOnload() : array
    {
        return self::$jsOnload;
    }

    public static function getJSHead() : array
    {
        return self::$jsHead;
    }

    public static function renderJSHead() : string
    {
        OutputBuffering::start();
        ?>
        <script>
            const APP_URL = '<?php echo FELHQM_WEBSERVER_URL ?>';
            const ImagePreviewer = new ImagePreview();
            <?php
            $statements = self::getJSHead();

            if(!empty($statements))
            {
                echo implode(';'.PHP_EOL, $statements).';';
            }

            $statements = self::getJSOnload();

            if(!empty($statements))
            {
                ?>
                $(function() {
                    <?php echo implode(';'.PHP_EOL, $statements) ?>;
                });
                <?php
            }
            ?>
        </script>
        <?php
        return OutputBuffering::get();
    }

    private static ?string $version = null;

    public static function getVersion() : string
    {
        if(!isset(self::$version))
        {
            self::$version = FileHelper::readContents(__DIR__ . '/../VERSION');
        }

        return self::$version;
    }

    public static function bool(bool $boolean) : string
    {
        if($boolean === true) {
            return 'YES';
        }

        return 'NO';
    }

    public static function getPageURL(string $urlName, array $params=array()) : string
    {
        $params[self::REQUEST_VAR_PAGE] = $urlName;

        return Request::getInstance()->buildURL($params);
    }

    /**
     * @return never
     */
    public static function exitApplication(string $reason) : void
    {
        FELHQM::log()->debug('Exiting application.');
        FELHQM::log()->debug(sprintf('Reason given: [%s]', $reason));
        exit;
    }

    /**
     * @param string|number|Interface_Stringable $message
     * @param string $type
     * @return void
     */
    public static function addMessage($message, string $type=Message::LAYOUT_PRIMARY) : void
    {
        if(!isset($_SESSION[self::SESSION_VAR_MESSAGES]))
        {
            $_SESSION[self::SESSION_VAR_MESSAGES] = array();
        }

        $_SESSION[self::SESSION_VAR_MESSAGES][] = array(
            'message' => (string)$message,
            'layout' => $type
        );
    }

    /**
     * @param string|number|Interface_Stringable $message
     * @return void
     */
    public static function addSuccessMessage($message) : void
    {
        self::addMessage($message, Message::LAYOUT_SUCCESS);
    }

    /**
     * @param string|number|Interface_Stringable $message
     * @return void
     */
    public static function addWarningMessage($message) : void
    {
        self::addMessage($message, Message::LAYOUT_WARNING);
    }

    /**
     * @param string|number|Interface_Stringable $message
     * @return void
     */
    public static function addInfoMessage($message) : void
    {
        self::addMessage($message, Message::LAYOUT_INFO);
    }

    /**
     * @param string|number|Interface_Stringable $message
     * @return void
     */
    public static function addErrorMessage($message) : void
    {
        self::addMessage($message, Message::LAYOUT_DANGER);
    }

    public static function displayMessages() : void
    {
        if(!isset($_SESSION[self::SESSION_VAR_MESSAGES]))
        {
            return;
        }

        foreach($_SESSION[self::SESSION_VAR_MESSAGES] as $messageDef)
        {
            self::message($messageDef['message'])
                ->setLayout($messageDef['layout'])
                ->display();
        }

        unset($_SESSION[self::SESSION_VAR_MESSAGES]);
    }
}
