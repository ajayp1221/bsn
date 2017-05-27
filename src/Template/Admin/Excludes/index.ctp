<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body">
                <div class="add_new"><?= $this->Html->link(__('Exclude ContactNo'), ['action' => 'contact']) ?> </div><br/>
                <div class="add_new" style="margin-top: 15px;margin-right: -88px;"><?= $this->Html->link(__('Exclude Store'), ['action' => 'store']) ?> </div>
                <h2 class="text-primary">SmsPlans Table</h2><br/>
                <div class="col-lg-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table no-margin"><thead>
                                    <tr>
                                        <th><?= $this->Paginator->sort('id') ?></th>
                                        <th><?= $this->Paginator->sort('model_name') ?></th>
                                        <th><?= $this->Paginator->sort('value') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($excludes as $exclude): ?>
                                        <tr>
                                            <td><?= $this->Number->format($exclude->id) ?></td>
                                            <td><?= h($exclude->model_name) ?></td>
                                            <td><?= h($exclude->value) ?></td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="paginator">
                    <ul class="pagination">
                        <?= $this->Paginator->prev('< ' . __('previous')) ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next(__('next') . ' >') ?>
                    </ul>
                    <p><?= $this->Paginator->counter() ?></p>
                </div>
            </div>
        </section>
    </div>
    <?= $this->element('side') ?>
</div>
<?= $this->element('tablejs') ?>