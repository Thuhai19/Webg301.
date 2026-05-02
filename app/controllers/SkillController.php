<?php

class SkillController extends Controller
{
    private Skill $model;

    public function __construct()
    {
        $this->model = new Skill();
    }

    public function index(): void
    {
        $this->requireAdmin();
        $allSkills = $this->model->all();
        $searchId = $this->get('search_id');
        if ($searchId !== null && $searchId !== '') {
            $displayId = (int) $searchId;
            if ($displayId > 0 && isset($allSkills[$displayId - 1])) {
                $skills = [$allSkills[$displayId - 1] + ['display_id' => $displayId]];
            } else {
                $skills = [];
            }
        } else {
            $skills = $allSkills;
        }
        $this->render('skills/index.php', [
            'skills' => $skills,
            'searchId' => $searchId,
            'pageTitle' => 'Skills',
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();
        $this->render('skills/form.php', ['skill' => null, 'pageTitle' => 'Add Skill']);
    }

    public function store(): void
    {
        $this->requireAdmin();
        if (!$this->isPost()) {
            $this->redirect($this->baseUrl() . 'index.php?controller=skill&action=index');
        }
        $name = trim((string) $this->post('name', ''));
        $damage = (int) $this->post('damage', 0);
        $mana = (int) $this->post('mana_cost', 0);
        if ($name === '') {
            $_SESSION['flash_error'] = 'Please enter a skill name.';
            $this->redirect($this->baseUrl() . 'index.php?controller=skill&action=create');
        }
        $this->model->create($name, max(0, $damage), max(0, $mana));
        $this->redirect($this->baseUrl() . 'index.php?controller=skill&action=index');
    }

    public function edit(): void
    {
        $this->requireAdmin();
        $id = (int) $this->get('id', 0);
        $skill = $this->model->findById($id);
        if (!$skill) {
            http_response_code(404);
            echo 'Skill not found.';
            return;
        }
        $this->render('skills/form.php', ['skill' => $skill, 'pageTitle' => 'Edit Skill']);
    }

    public function update(): void
    {
        $this->requireAdmin();
        if (!$this->isPost()) {
            $this->redirect($this->baseUrl() . 'index.php?controller=skill&action=index');
        }
        $id = (int) $this->post('id', 0);
        $name = trim((string) $this->post('name', ''));
        $damage = (int) $this->post('damage', 0);
        $mana = (int) $this->post('mana_cost', 0);
        if ($id <= 0 || $name === '') {
            $_SESSION['flash_error'] = 'Invalid data.';
            $this->redirect($this->baseUrl() . 'index.php?controller=skill&action=index');
        }
        $this->model->update($id, $name, max(0, $damage), max(0, $mana));
        $this->redirect($this->baseUrl() . 'index.php?controller=skill&action=index');
    }

    public function delete(): void
    {
        $this->requireAdmin();
        $id = (int) $this->get('id', 0);
        if ($id > 0) {
            $this->model->delete($id);
        }
        $this->redirect($this->baseUrl() . 'index.php?controller=skill&action=index');
    }
}
