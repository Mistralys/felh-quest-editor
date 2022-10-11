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

require_once 'vendor/autoload.php';
require_once 'config.php';

try
{
    UI::setShowXMLTags(FELHQM_SHOW_XML_TAGS);

    $reader = FilesReader::create()
        ->selectAllOffical();

    $request = new Request();
    $request->setBaseURL(FELHQM_WEBSERVER_URL);
    $appName = 'FELH Quest Editor';

    $pageID = $request->getParam('page');

    switch ($pageID)
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
        <script>
            const APP_URL = '<?php echo FELHQM_WEBSERVER_URL ?>';
            const ImagePreviewer = new ImagePreview();
            $(document).ready(function() {
                <?php echo implode(PHP_EOL, UI::getJSAutoLoad()) ?>
            });
        </script>
    </head>
    <body>
        <h1><?php echo $page->getTitle() ?></h1>
        <?php echo $content ?>
    </body>
</html>