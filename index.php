<?php

declare(strict_types=1);

use AppUtils\Request;
use Mistralys\FELHQuestEditor\FilesReader;
use Mistralys\FELHQuestEditor\UI;
use Mistralys\FELHQuestEditor\UI\Pages\EditQuest;
use Mistralys\FELHQuestEditor\UI\Pages\ExceptionPage;
use Mistralys\FELHQuestEditor\UI\Pages\QuestsList;
use Mistralys\FELHQuestEditor\UI\Pages\ViewGraphicFile;
use Mistralys\FELHQuestEditor\UI\Pages\ViewRawData;
use AppLocalize\Localization;
use function AppLocalize\t;

require_once 'vendor/autoload.php';
require_once 'config.php';

try
{
    $source = Localization::addSourceFolder(
        'felhqe-classes',
        'FELHQE Classes',
        'FELHQE',
        __DIR__.'/localization',
        __DIR__.'/src'
    );

    Localization::configure(
        __DIR__.'/localization/cache.json',
        __DIR__.'/js'
    );

    Localization::setClientLibrariesCacheKey(UI::getVersion());

    UI::setShowXMLTags(FELHQM_SHOW_XML_TAGS);

    $reader = FilesReader::create()
        ->selectAllOffical();

    $request = new Request();
    $request->setBaseURL(FELHQM_WEBSERVER_URL);
    $appName = t('FELH Quest Editor');

    $activePageID = $request->getParam('page');

    switch ($activePageID)
    {
        case ViewGraphicFile::URL_NAME:
            $page = new ViewGraphicFile();
            break;

        case ViewRawData::URL_NAME:
            $page = new ViewRawData($reader);
            break;

        case EditQuest::URL_NAME:
            $collection = $reader->getCollection();
            $page = new EditQuest($collection->requireByRequest());
            break;

        default:
            $activePageID = QuestsList::URL_NAME;
            $collection = $reader->getCollection();
            $page = new QuestsList($collection);
            break;
    }

    $content = $page->render();
}
catch (Throwable $e)
{
    ob_end_clean();

    $page = new ExceptionPage($e);
    $content = $page->render();
}

?><html lang="en">
    <head>
        <title><?php echo $appName ?></title>
        <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/ui.css">
        <link rel="stylesheet" href="vendor/fortawesome/font-awesome/css/fontawesome.min.css">
        <link rel="stylesheet" href="vendor/fortawesome/font-awesome/css/all.min.css">
        <script src="vendor/components/jquery/jquery.min.js"></script>
        <script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="js/image-preview.js"></script>
        <script src="js/enum-attribute.js"></script>
        <script>
            const APP_URL = '<?php echo FELHQM_WEBSERVER_URL ?>';
            const ImagePreviewer = new ImagePreview();
            <?php
            $statements = UI::getJSHead();

            if(!empty($statements))
            {
                echo implode(';'.PHP_EOL, $statements).';';
            }

            $statements = UI::getJSOnload();

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
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><?php echo $appName ?></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <?php
                        $navItems = array(
                            QuestsList::URL_NAME => t('Quests list'),
                            ViewRawData::URL_NAME => t('Raw data')
                        );

                        foreach ($navItems as $urlName => $label)
                        {
                            $isActive = $urlName === $activePageID;

                            ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if($isActive) { echo 'active'; } ?>" href="?page=<?php echo $urlName ?>">
                                    <?php echo $label ?>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>

        <h1><?php echo $page->getTitle() ?></h1>
        <?php echo $content ?>
    </body>
</html>