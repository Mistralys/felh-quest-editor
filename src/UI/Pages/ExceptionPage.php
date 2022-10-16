<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor\UI\Pages;

use AppUtils\ConvertHelper_ThrowableInfo;
use Mistralys\FELHQuestEditor\UI\BasePage;
use Throwable;
use function AppLocalize\pt;
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
        <h2>Calls</h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Number</th>
                    <th class="align-right"><?php pt('Class'); ?></th>
                    <th>File</th>
                    <th>Function</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $calls = $this->info->getCalls();

            foreach($calls as $call)
            {
                ?>
                <tr>
                    <td><?php echo $call->getPosition() ?></td>
                    <td class="align-right"><?php echo $call->getClass() ?></td>
                    <td><?php echo $call->getFileName() ?>:<?php echo $call->getLine() ?></td>
                    <td><?php echo $call->getFunction() ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <?php
    }
}
