<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th width="30%">Customer ID</th>
            <td><?= $model->customerID ?></td>
        </tr>

        <tr>
            <th>Full Name</th>
            <td><?= $model->full_name ?></td>
        </tr>

        <tr>
            <th>Birth Date</th>
            <td><?= Yii::$app->formatter->asDate($model->birthDate) ?></td>
        </tr>

        <tr>
            <th>Gender</th>
            <td><?= ucfirst($model->gender) ?></td>
        </tr>

        <tr>
            <th>Contacts</th>
            <td><?= $model->contacts ?></td>
        </tr>

        <tr>
            <th>Address</th>
            <td><?= $model->address ?></td>
        </tr>

        <tr>
            <th>NIN</th>
            <td><?= $model->NIN ?></td>
        </tr>

        <tr>
            <th>TIN</th>
            <td><?= $model->TIN ?: '-' ?></td>
        </tr>

        <tr>
            <th>Status</th>
            <td>
                <span class="badge <?= $model->isStatusActive() ? 'badge-success' : 'badge-secondary' ?>">
                    <?= $model->displayStatus() ?>
                </span>
            </td>
        </tr>


        <tr>
            <th>Total Loans</th>
            <td><?= count($model->customerLoans) ?></td>
        </tr>

        <tr>
            <th>Shareholder</th>
            <td><?= $model->shareholder ? 'Yes' : 'No' ?></td>
        </tr>

        <?php if ($model->isDeleted): ?>
        <tr>
            <th>Deleted At</th>
            <td><?= Yii::$app->formatter->asDatetime($model->deleted_at) ?></td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
