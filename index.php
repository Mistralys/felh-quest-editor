<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use Mistralys\FELHQuestEditor\UI\MainNavigation;
use Mistralys\FELHQuestEditor\UI\PageFactory;
use Mistralys\FELHQuestEditor\UI\Pages\ExceptionPage;
use AppLocalize\Localization;
use Throwable;

require_once 'vendor/autoload.php';
require_once 'config.php';

const FELHQM_CACHE_FOLDER = __DIR__.'/cache';

$activePageID = null;

$request = new Request();
$request->setBaseURL(FELHQM_WEBSERVER_URL);

session_start();

Localization::addSourceFolder(
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

try
{
    UI::setShowXMLTags(FELHQM_SHOW_XML_TAGS);

    $reader = FilesReader::create()
        ->selectAllOffical();

    $instantiator = new PageFactory($request, $reader);
    $page = $instantiator->instantiate();
    $page->handleActions();
    $activePageID = $instantiator->getActivePageID();

    $content = $page->render();
}
catch (Throwable $e)
{
    ob_end_clean();

    $page = new ExceptionPage($request, $e);
    $content = $page->render();
}

?><html lang="en">
    <head>
        <title><?php echo FELHQM::getName() ?></title>
        <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/ui.css">
        <link rel="stylesheet" href="vendor/fortawesome/font-awesome/css/fontawesome.min.css">
        <link rel="stylesheet" href="vendor/fortawesome/font-awesome/css/all.min.css">
        <script src="vendor/components/jquery/jquery.min.js"></script>
        <script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="js/image-preview.js"></script>
        <script src="js/enum-attribute.js"></script>
        <?php echo UI::renderJSHead() ?>
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><?php echo FELHQM::getShortName() ?></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <?php
                        $nav = new MainNavigation();
                        $navItems = $nav->getItems();

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
        <?php

        UI::displayMessages();

        $abstract = $page->getAbstract();
        ?>
        <h1 class="page-title <?php if(!empty($abstract)) { echo 'with-abstract'; } ?>">
            <?php echo $page->getTitle() ?>
        </h1>
        <?php

        if(!empty($abstract)) {
            ?>
            <p class="page-abstract"><?php echo $abstract ?></p>
            <?php
        }
        ?>
        <?php echo $content ?>
    </body>
</html>