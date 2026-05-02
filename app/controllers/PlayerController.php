<?php

class PlayerController extends Controller
{
    private Player $model;
    private Character $characterModel;
    private Item $itemModel;

    public function __construct()
    {
        $this->model = new Player();
        $this->characterModel = new Character();
        $this->itemModel = new Item();
    }

    public function index(): void
    {
        $this->requireAdmin();
        $allPlayers = $this->model->all();
        $searchId = $this->get('search_id');
        if ($searchId !== null && $searchId !== '') {
            $displayId = (int) $searchId;
            if ($displayId > 0 && isset($allPlayers[$displayId - 1])) {
                $players = [$allPlayers[$displayId - 1] + ['display_id' => $displayId]];
            } else {
                $players = [];
            }
        } else {
            $players = $allPlayers;
        }
        $this->render('players/index.php', [
            'players' => $players,
            'searchId' => $searchId,
            'pageTitle' => 'Players',
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();
        $this->render('players/form.php', [
            'player' => null,
            'characters' => $this->characterModel->allWithPlayer(),
            'items' => $this->itemModel->all(),
            'pageTitle' => 'Add Player',
        ]);
    }

    public function store(): void
    {
        $this->requireAdmin();
        if (!$this->isPost()) {
            $this->redirect($this->baseUrl() . 'index.php?controller=player&action=index');
        }
        $name = trim((string) $this->post('name', ''));
        $email = trim((string) $this->post('email', ''));
        $killCharacterId = (int) $this->post('kill_character_id', 0);
        $itemId = (int) $this->post('item_id', 0);
        if ($name === '' || $email === '') {
            $_SESSION['flash_error'] = 'Please fill in all required fields.';
            $this->redirect($this->baseUrl() . 'index.php?controller=player&action=create');
        }
        $newId = $this->model->create($name, $email, $killCharacterId > 0 ? $killCharacterId : null, $itemId > 0 ? $itemId : null);
        $this->syncLinksFromPlayer($newId, $killCharacterId > 0 ? $killCharacterId : null, $itemId > 0 ? $itemId : null);
        $this->redirect($this->baseUrl() . 'index.php?controller=player&action=index');
    }

    public function edit(): void
    {
        $this->requireAdmin();
        $id = (int) $this->get('id', 0);
        $player = $this->model->findById($id);
        if (!$player) {
            http_response_code(404);
            echo 'Player not found.';
            return;
        }
        $this->render('players/form.php', [
            'player' => $player,
            'characters' => $this->characterModel->allWithPlayer(),
            'items' => $this->itemModel->all(),
            'pageTitle' => 'Edit Player',
        ]);
    }

    public function update(): void
    {
        $this->requireAdmin();
        if (!$this->isPost()) {
            $this->redirect($this->baseUrl() . 'index.php?controller=player&action=index');
        }
        $id = (int) $this->post('id', 0);
        $name = trim((string) $this->post('name', ''));
        $email = trim((string) $this->post('email', ''));
        $killCharacterId = (int) $this->post('kill_character_id', 0);
        $itemId = (int) $this->post('item_id', 0);
        if ($id <= 0 || $name === '' || $email === '') {
            $_SESSION['flash_error'] = 'Invalid data.';
            $this->redirect($this->baseUrl() . 'index.php?controller=player&action=index');
        }
        $finalKillCharacterId = $killCharacterId > 0 ? $killCharacterId : null;
        $finalItemId = $itemId > 0 ? $itemId : null;
        $this->model->update($id, $name, $email, $finalKillCharacterId, $finalItemId);
        $this->syncLinksFromPlayer($id, $finalKillCharacterId, $finalItemId);
        $this->redirect($this->baseUrl() . 'index.php?controller=player&action=index');
    }

    public function delete(): void
    {
        $this->requireAdmin();
        $id = (int) $this->get('id', 0);
        if ($id > 0) {
            $this->model->delete($id);
        }
        $this->redirect($this->baseUrl() . 'index.php?controller=player&action=index');
    }

    public function show(): void
    {
        $this->requireAdmin();
        $id = (int) $this->get('id', 0);
        $player = $this->model->findById($id);
        if (!$player) {
            http_response_code(404);
            echo 'Player not found.';
            return;
        }
        $this->render('players/show.php', ['player' => $player, 'pageTitle' => 'Player Details']);
    }

    private function syncLinksFromPlayer(int $playerId, ?int $killCharacterId, ?int $itemId): void
    {
        if ($killCharacterId === null) {
            $this->characterModel->clearKillByPlayerId($playerId);
            return;
        }

        // Keep one-to-one mapping in both directions.
        $this->characterModel->clearKillByPlayerId($playerId, $killCharacterId);
        $this->model->clearKillCharacterByCharacterId($killCharacterId, $playerId);
        $this->characterModel->setKillByPlayerId($killCharacterId, $playerId);
        $this->model->setKillCharacterId($playerId, $killCharacterId);

        // Item sync: selecting item on player also assigns that item to the linked character.
        if ($itemId !== null) {
            $this->characterModel->attachItem($killCharacterId, $itemId);
        }
    }
}
