<?php

class ItemController extends Controller
{
    private Item $model;

    public function __construct()
    {
        $this->model = new Item();
    }

    public function index(): void
    {
        $this->requireAdmin();
        $allItems = $this->model->all();
        $searchId = $this->get('search_id');
        if ($searchId !== null && $searchId !== '') {
            $displayId = (int) $searchId;
            if ($displayId > 0 && isset($allItems[$displayId - 1])) {
                $items = [$allItems[$displayId - 1] + ['display_id' => $displayId]];
            } else {
                $items = [];
            }
        } else {
            $items = $allItems;
        }
        $this->render('items/index.php', [
            'items' => $items,
            'searchId' => $searchId,
            'pageTitle' => 'Items',
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();
        $this->render('items/form.php', ['item' => null, 'pageTitle' => 'Add Item']);
    }

    public function store(): void
    {
        $this->requireAdmin();
        if (!$this->isPost()) {
            $this->redirect($this->baseUrl() . 'index.php?controller=item&action=index');
        }
        $name = trim((string) $this->post('name', ''));
        $type = trim((string) $this->post('type', ''));
        $power = (int) $this->post('power', 0);
        if ($name === '' || $type === '') {
            $_SESSION['flash_error'] = 'Please fill in all required fields.';
            $this->redirect($this->baseUrl() . 'index.php?controller=item&action=create');
        }
        $this->model->create($name, $type, max(0, $power));
        $this->redirect($this->baseUrl() . 'index.php?controller=item&action=index');
    }

    public function edit(): void
    {
        $this->requireAdmin();
        $id = (int) $this->get('id', 0);
        $item = $this->model->findById($id);
        if (!$item) {
            http_response_code(404);
            echo 'Item not found.';
            return;
        }
        $this->render('items/form.php', ['item' => $item, 'pageTitle' => 'Edit Item']);
    }

    public function update(): void
    {
        $this->requireAdmin();
        if (!$this->isPost()) {
            $this->redirect($this->baseUrl() . 'index.php?controller=item&action=index');
        }
        $id = (int) $this->post('id', 0);
        $name = trim((string) $this->post('name', ''));
        $type = trim((string) $this->post('type', ''));
        $power = (int) $this->post('power', 0);
        if ($id <= 0 || $name === '' || $type === '') {
            $_SESSION['flash_error'] = 'Invalid data.';
            $this->redirect($this->baseUrl() . 'index.php?controller=item&action=index');
        }
        $this->model->update($id, $name, $type, max(0, $power));
        $this->redirect($this->baseUrl() . 'index.php?controller=item&action=index');
    }

    public function delete(): void
    {
        $this->requireAdmin();
        $id = (int) $this->get('id', 0);
        if ($id > 0) {
            $this->model->delete($id);
        }
        $this->redirect($this->baseUrl() . 'index.php?controller=item&action=index');
    }
}
