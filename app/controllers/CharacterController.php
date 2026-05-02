<?php

class CharacterController extends Controller
{
    private Character $charModel;
    private Skill $skillModel;
    private Item $itemModel;
    private Player $playerModel;

    public function __construct()
    {
        $this->charModel = new Character();
        $this->skillModel = new Skill();
        $this->itemModel = new Item();
        $this->playerModel = new Player();
    }

    public function index(): void
    {
        $this->requireAdmin();
        $allCharacters = $this->charModel->allWithPlayer();
        $searchId = $this->get('search_id');
        if ($searchId !== null && $searchId !== '') {
            $displayId = (int) $searchId;
            if ($displayId > 0 && isset($allCharacters[$displayId - 1])) {
                $characters = [$allCharacters[$displayId - 1] + ['display_id' => $displayId]];
            } else {
                $characters = [];
            }
        } else {
            $characters = $allCharacters;
        }
        $this->render('characters/index.php', [
            'characters' => $characters,
            'searchId' => $searchId,
            'pageTitle' => 'Characters',
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();
        $this->render('characters/form.php', [
            'character' => null,
            'players' => $this->playerModel->all(),
            'pageTitle' => 'Add Character',
        ]);
    }

    public function store(): void
    {
        $this->requireAdmin();
        if (!$this->isPost()) {
            $this->redirect($this->baseUrl() . 'index.php?controller=character&action=index');
        }
        $d = $this->collectPost();
        if ($d === null) {
            $_SESSION['flash_error'] = 'Invalid data.';
            $this->redirect($this->baseUrl() . 'index.php?controller=character&action=create');
        }
        $newId = $this->charModel->create($d);
        $this->syncLinksFromCharacter($newId, $d['kill_by_player_id']);
        $this->redirect($this->baseUrl() . 'index.php?controller=character&action=index');
    }

    public function edit(): void
    {
        $this->requireAdmin();
        $id = (int) $this->get('id', 0);
        $character = $this->charModel->findById($id);
        if (!$character) {
            http_response_code(404);
            echo 'Character not found.';
            return;
        }
        $this->render('characters/form.php', [
            'character' => $character,
            'players' => $this->playerModel->all(),
            'pageTitle' => 'Edit Character',
        ]);
    }

    public function update(): void
    {
        $this->requireAdmin();
        if (!$this->isPost()) {
            $this->redirect($this->baseUrl() . 'index.php?controller=character&action=index');
        }
        $id = (int) $this->post('id', 0);
        $d = $this->collectPost();
        if ($id <= 0 || $d === null) {
            $_SESSION['flash_error'] = 'Invalid data.';
            $this->redirect($this->baseUrl() . 'index.php?controller=character&action=index');
        }
        $this->charModel->update($id, $d);
        $this->syncLinksFromCharacter($id, $d['kill_by_player_id']);
        $this->redirect($this->baseUrl() . 'index.php?controller=character&action=index');
    }

    public function delete(): void
    {
        $this->requireAdmin();
        $id = (int) $this->get('id', 0);
        if ($id > 0) {
            $this->charModel->delete($id);
        }
        $this->redirect($this->baseUrl() . 'index.php?controller=character&action=index');
    }

    public function show(): void
    {
        $this->requireAdmin();
        $id = (int) $this->get('id', 0);
        $character = $this->charModel->findById($id);
        if (!$character) {
            http_response_code(404);
            echo 'Character not found.';
            return;
        }
        $skills = $this->charModel->getSkillsForCharacter($id);
        $items = $this->charModel->getItemsForCharacter($id);
        $allSkills = $this->skillModel->all();
        $allItems = $this->itemModel->all();
        $this->render('characters/show.php', [
            'character' => $character,
            'skills' => $skills,
            'items' => $items,
            'allSkills' => $allSkills,
            'allItems' => $allItems,
            'pageTitle' => 'Character Details',
        ]);
    }

    public function attachSkill(): void
    {
        $this->requireAdmin();
        if (!$this->isPost()) {
            $this->redirect($this->baseUrl() . 'index.php?controller=character&action=index');
        }
        $cid = (int) $this->post('character_id', 0);
        $sid = (int) $this->post('skill_id', 0);
        if ($cid > 0 && $sid > 0) {
            $this->charModel->attachSkill($cid, $sid);
        }
        $this->redirect($this->baseUrl() . 'index.php?controller=character&action=show&id=' . $cid);
    }

    public function detachSkill(): void
    {
        $this->requireAdmin();
        $cid = (int) $this->get('character_id', 0);
        $sid = (int) $this->get('skill_id', 0);
        if ($cid > 0 && $sid > 0) {
            $this->charModel->detachSkill($cid, $sid);
        }
        $this->redirect($this->baseUrl() . 'index.php?controller=character&action=show&id=' . $cid);
    }

    public function attachItem(): void
    {
        $this->requireAdmin();
        if (!$this->isPost()) {
            $this->redirect($this->baseUrl() . 'index.php?controller=character&action=index');
        }
        $cid = (int) $this->post('character_id', 0);
        $iid = (int) $this->post('item_id', 0);
        if ($cid > 0 && $iid > 0) {
            $this->charModel->attachItem($cid, $iid);
            $character = $this->charModel->findById($cid);
            if (!empty($character['kill_by_player_id'])) {
                $this->playerModel->setItemId((int) $character['kill_by_player_id'], $iid);
            }
        }
        $this->redirect($this->baseUrl() . 'index.php?controller=character&action=show&id=' . $cid);
    }

    public function detachItem(): void
    {
        $this->requireAdmin();
        $cid = (int) $this->get('character_id', 0);
        $iid = (int) $this->get('item_id', 0);
        if ($cid > 0 && $iid > 0) {
            $this->charModel->detachItem($cid, $iid);
            $character = $this->charModel->findById($cid);
            if (!empty($character['kill_by_player_id'])) {
                $playerId = (int) $character['kill_by_player_id'];
                $currentItemId = $this->playerModel->getItemId($playerId);
                if ($currentItemId === $iid) {
                    $this->playerModel->setItemId($playerId, null);
                }
            }
        }
        $this->redirect($this->baseUrl() . 'index.php?controller=character&action=show&id=' . $cid);
    }

    private function collectPost(): ?array
    {
        $name = trim((string) $this->post('name', ''));
        $level = (int) $this->post('level', 1);
        $hp = (int) $this->post('hp', 0);
        $mana = (int) $this->post('mana', 0);
        $killByPlayerId = (int) $this->post('kill_by_player_id', 0);
        if ($name === '') {
            return null;
        }
        return [
            'name' => $name,
            'level' => max(1, $level),
            'hp' => max(0, $hp),
            'mana' => max(0, $mana),
            'kill_by_player_id' => $killByPlayerId > 0 ? $killByPlayerId : null,
        ];
    }

    private function syncLinksFromCharacter(int $characterId, ?int $killByPlayerId): void
    {
        if ($killByPlayerId === null) {
            $this->playerModel->clearKillCharacterByCharacterId($characterId);
            return;
        }

        // Keep one-to-one mapping in both directions.
        $this->charModel->clearKillByPlayerId($killByPlayerId, $characterId);
        $this->playerModel->clearKillCharacterByCharacterId($characterId, $killByPlayerId);
        $this->playerModel->setKillCharacterId($killByPlayerId, $characterId);
        $this->charModel->setKillByPlayerId($characterId, $killByPlayerId);

        // Item sync from player to linked character.
        $itemId = $this->playerModel->getItemId($killByPlayerId);
        if ($itemId !== null) {
            $this->charModel->attachItem($characterId, $itemId);
        }
    }
}
