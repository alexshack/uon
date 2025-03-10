<?php


get_header();

$author_id = get_user_by( 'slug', get_query_var( 'author_name' ) )->ID;
$user = new UonUser( $author_id, ['all' => true]);
?>
<main id="site-content">
    <div class="container">
    <article>
        <h1 class="mb-3"><?php echo $user->name ?></h1></h1>

    <ul class="nav nav-tabs " id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Личные данные</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Туристы</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">История поездок</button>
  </li>
</ul>
<div class="tab-content user-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
      <form class="mt-3">
          <div class="row mb-3">
              <label for="first_name" class="col-sm-2 col-form-label">Имя</label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user->user->first_name ?>">
              </div>
          </div>
          <div class="row mb-3">
              <label for="sname" class="col-sm-2 col-form-label">Отчество</label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="sname" name="sname" value="<?php echo $user->__get('sname') ?>">
              </div>
          </div>
          <div class="row mb-3">
              <label for="last_name" class="col-sm-2 col-form-label">Фамилия</label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user->user->last_name ?>">
              </div>
          </div>
          <div class="row mb-3">
              <label for="phone" class="col-sm-2 col-form-label">Телефон</label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user->__get('phone') ?>">
              </div>
          </div>
          <div class="row mb-3">
              <label for="user_email" class="col-sm-2 col-form-label">Email</label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="user_email" name="user_email" value="<?php echo $user->user->user_email ?>">
              </div>
          </div>
          <div class="row mb-3">
              <label for="passport_number" class="col-sm-2 col-form-label">Номер паспорта</label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="passport_number" name="passport_number" value="<?php echo $user->__get('passport_number') ?>">
              </div>
          </div>
          <div class="row mb-3">
              <label for="passport_taken" class="col-sm-2 col-form-label">Кем выдан</label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="passport_taken" name="passport_taken" value="<?php echo $user->__get('passport_taken') ?>">
              </div>
          </div>
          <div class="row mb-3">
              <label for="passport_date" class="col-sm-2 col-form-label">Когда выдан</label>
              <div class="col-sm-10">
                  <input type="date" class="form-control" id="passport_date" name="passport_date" value="<?php echo date('Y-m-d', strtotime($user->__get('passport_date'))) ?>">
              </div>
          </div>
          <div class="row mb-3">
              <label for="passport_code" class="col-sm-2 col-form-label">Код подразделения</label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="passport_code" name="passport_code" value="<?php echo $user->__get('passport_code') ?>">
              </div>
          </div>
          <div class="row mb-3">
              <label for="address" class="col-sm-2 col-form-label">Адрес регистрации</label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="address" name="address" value="<?php echo $user->__get('address') ?>">
              </div>
          </div>
          <button type="submit" class="btn btn-primary">Сохранить</button>
      </form>
  </div>
  <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
      <table class="table table-striped">
          <thead>
          <tr>
              <th scope="col">Имя</th>
              <th scope="col">День рождения</th>
          </tr>
          </thead>
          <tbody>
      <?php foreach($user->tourists as $tourist): ?>
          <tr>
              <td><a href="#>"><?php echo $tourist->name ?></a></td>
              <td><?php echo date('d.m.Y', strtotime($tourist->__get('birthday'))) ?></td>
          </tr>
      <?php endforeach; ?>
          </tbody>
      </table>
  </div>
  <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
      <table class="table table-striped">
          <thead>
          <tr>
              <th scope="col">Поездка</th>
              <th scope="col">Программа</th>
              <th scope="col">Тип</th>
              <th scope="col">Даты</th>
              <th scope="col">Стоимость</th>
              <th scope="col">Статус</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach($user->orders as $order): ?>
              <tr>
                  <td><a href="#>"><?php echo $order->post->post_title ?></a></td>
                  <td><?php echo $order->program->name ?></td>
                  <td><?php echo $order->__get('travel_type') ?></td>
                  <td><?php echo date('d.m.Y', strtotime($order->__get('date_begin'))).'-'.date('d.m.Y', strtotime($order->__get('date_end'))) ?></td>
                  <td><?php echo $order->__get('price') ?></td>
                  <td><?php echo $order->__get('status') ?></td>
              </tr>
          <?php endforeach; ?>
          </tbody>
      </table>
  </div>
</div>

    </article>
    </div>
</main><!-- #site-content -->



<?php
get_footer();
