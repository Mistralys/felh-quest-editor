<?php

declare(strict_types=1);

namespace Mistralys\FELHQuestEditor;

use AppUtils\Request;

class QuestsCollection
{
    public const ERROR_QUEST_ID_NOT_FOUND = 119201;
    public const ERROR_NO_QUEST_IN_REQUEST = 119202;
    public const REQUEST_VAR_QUEST_ID = 'quest';

    /**
     * @var Quest[]
     */
    private array $quests;

    /**
     * @param Quest[] $quests
     */
    public function __construct(array $quests)
    {
        $this->quests = $quests;
    }

    /**
     * @return Quest[]
     */
    public function getAll() : array
    {
        return $this->quests;
    }

    public function requireByRequest() : Quest
    {
        $id = (string)Request::getInstance()->getParam(self::REQUEST_VAR_QUEST_ID);

        if(!empty($id) && $this->idExists($id)) {
            return $this->getByID($id);
        }

        throw new QuestEditorException(
            'No quest specified in the request.',
            '',
            self::ERROR_NO_QUEST_IN_REQUEST
        );
    }

    public function idExists(string $id) : bool
    {
        foreach($this->quests as $quest) {
            if($quest->getInternalName() === $id) {
                return true;
            }
        }

        return false;
    }

    public function getByID(string $id) : Quest
    {
        foreach($this->quests as $quest) {
            if($quest->getInternalName() === $id) {
                return $quest;
            }
        }

        throw new QuestEditorException(
            'Quest not found.',
            sprintf(
                'The quest [%s] was not found.',
                $id
            ),
            self::ERROR_QUEST_ID_NOT_FOUND
        );
    }

    public function add(Quest $quest) : self
    {
        $this->quests[] = $quest;
        return $this;
    }
}
