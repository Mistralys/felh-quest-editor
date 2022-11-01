<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use AppUtils\Interface_Stringable;
use function AppLocalize\pt;

class Request extends \AppUtils\Request
{
    /**
     * @param string $url
     * @param string|number|Interface_Stringable $message
     * @return never
     */
    public function redirectWithSuccessMessage(string $url, $message) : void
    {
        UI::addSuccessMessage($message);
        $this->redirectTo($url);
    }

    /**
     * @param string $url
     * @return never
     */
    public function redirectTo(string $url) : void
    {
        if(!headers_sent())
        {
            header('Location: '.$url);
        }
        else
        {
            ?>
            <p>
                <a href="<?php echo $url ?>">
                    <?php pt('Click here to continue'); ?>
                </a>
            </p>
            <?php
        }

        UI::exitApplication(sprintf('Redirect to [%s].', $url));
    }
}
