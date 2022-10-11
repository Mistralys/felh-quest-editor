<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI\Pages;

use AppUtils\ConvertHelper_ThrowableInfo;
use Mistralys\FELHQuestEditor\UI\BasePage;
use Throwable;
use function AppUtils\parseThrowable;

class ExceptionPage extends BasePage
{
    private ConvertHelper_ThrowableInfo $info;

    public function __construct(Throwable $e)
    {
        $this->info = parseThrowable($e);

        parent::__construct();
    }

    public function getTitle() : string
    {
        return 'System error';
    }

    public function display() : void
    {
        ?>
        <pre><?php echo $this->info->renderErrorMessage(true); ?></pre>
        <?php
    }
}
