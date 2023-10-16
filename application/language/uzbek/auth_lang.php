<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Name:  Auth Lang - Russian
 *
 * Author: Ben Edmunds
 * 		  ben.edmunds@gmail.com
 *         @benedmunds
 *
 * Author: Daniel Davis
 *         @ourmaninjapan
 *
 * Translation: Ievgen Sentiabov
 *         @joni-jones
 *
 * Location: http://github.com/benedmunds/ion_auth/
 *
 * Created:  03.09.2013
 *
 * Description:  Russian language file for Ion Auth views
 *
 */

// Errors
$lang['error_csrf'] = 'Форма не прошла проверку безопасности.';

// Login
$lang['login_heading']         = 'Вход';
$lang['login_subheading']      = 'Для входа используйте email/имя пользователя и пароль.';
$lang['login_identity_label']  = 'Email:';
$lang['login_password_label']  = 'Пароль:';
$lang['login_remember_label']  = 'Запомнить меня:';
$lang['login_submit_btn']      = 'Вход';
$lang['login_forgot_password'] = 'Забыли свой пароль?';

// Index
$lang['index_heading']           = 'Пользователь';
$lang['index_subheading']        = 'Доступный список пользователей.';
$lang['index_ID_th']             = 'ID';
$lang['index_fname_th']          = 'Исм';
$lang['index_lname_th']          = 'Фамилия';
$lang['index_surname_th']        = 'Отасининг исми';
$lang['index_email_th']          = 'Email';
$lang['index_age_th']            = 'Ёш';
$lang['index_address_th']        = 'Манзил';
$lang['index_phone_th']          = 'Телефон';
$lang['index_sum_th']            = 'Сумма';
$lang['index_paid']              = 'Тўланди';
$lang['index_payment']           = 'Тўлов';
$lang['index_last_payment']      = 'Охирги тўлов';
$lang['index_payment_id_th']     = 'Чек No';
$lang['index_company_th']        = 'Ташкилот номи';
$lang['index_description_th']    = 'Кискача маълумот';
$lang['index_job_title_th']      = 'Мутахассислиги';
$lang['index_agreement_th']      = 'Келишув %';
$lang['index_partner_department_th'] = 'Хамкор тури';
$lang['index_department_name']    = 'Бўлим номи';

$lang['index_groups_th']         = 'Группы';
$lang['index_status_th']         = 'Статус';
$lang['index_action_th']         = 'Действие';
$lang['index_active_link']       = 'Активный';
$lang['index_inactive_link']     = 'Неактивный';
$lang['index_create_user_link']  = 'Создать нового пользователя';
$lang['index_create_group_link'] = 'Создать новую группу';

// Deactivate User
$lang['deactivate_heading']                  = 'Деактивировать пользователя';
$lang['deactivate_subheading']               = 'Вы уверены, что хотите деактивировать пользователя \'%s\'';
$lang['deactivate_confirm_y_label']          = 'Да:';
$lang['deactivate_confirm_n_label']          = 'Нет:';
$lang['deactivate_submit_btn']               = 'Отправить';
$lang['deactivate_validation_confirm_label'] = 'подтверждение';
$lang['deactivate_validation_user_id_label'] = 'ID пользователя';

// Create User
$lang['create_user_heading']                           = 'Фойдаланувчи яратиш';
$lang['create_user_subheading']                        = 'Пожалуйста заполните следующую информацию.';
$lang['create_user_fname_label']                       = 'Исм:';
$lang['create_user_lname_label']                       = 'Фамилия:';
$lang['create_user_surname_label']                     = 'Отасининг исми:';
$lang['create_user_identity_label']                    = 'Identity:';
$lang['create_user_company_label']                     = 'Ташкилот номи:';
$lang['create_user_email_label']                       = 'Email:';
$lang['create_user_phone_label']                       = 'Телефон:';
$lang['create_user_password_label']                    = 'Парол:';
$lang['create_user_password_confirm_label']            = 'Паролни тасдикланг:';
$lang['create_user_submit_btn']                        = 'Саклаш ва Чек чикариш';
$lang['create_user_dob_label']                         = 'Тугилган сана:';
$lang['create_user_gender_label']                      = 'Жинси:';
$lang['create_user_gender_male_label']                 = 'Эркак';
$lang['create_user_gender_female_label']               = 'Аёл';
$lang['create_user_postcode_label']                    = 'Индекс:';
$lang['create_user_address_label']                     = 'Манзил:';
$lang['create_user_region_label']                      = 'Вилоят:';
$lang['create_user_city_label']                        = 'Шахар:';
$lang['create_user_description_label']                 = 'Кискача маълумот:';
$lang['create_user_doctors_types_label']               = 'Мутахассислиги:';
$lang['create_user_doctor_price_label']                = 'Қабул нархи:';
$lang['create_user_doctor_percent_label']              = 'Келишув %:';
$lang['create_user_doctor_group_label']                = 'Гурух (ҳуқуқлар):';
$lang['create_user_partner_type_label']                = 'Хамкор тури:';
$lang['create_user_payment_type_label']                = 'Тўлов тури:';
$lang['create_user_department_name_label']             = 'Бўлим номи:';



$lang['create_user_validation_fname_label']            = 'Исм';
$lang['create_user_validation_lname_label']            = 'Фамилия';
$lang['create_user_validation_surname']                = 'Отасининг исми:';
$lang['create_user_validation_identity_label']         = 'Identity';
$lang['create_user_validation_email_label']            = 'Email';
$lang['create_user_validation_phone1_label']           = 'Первая часть телефона';
$lang['create_user_validation_phone2_label']           = 'Вторая часть телефона';
$lang['create_user_validation_phone3_label']           = 'Третья часть телефона';
$lang['create_user_validation_company_label']          = 'Компания';
$lang['create_user_validation_password_label']         = 'Пароль';
$lang['create_user_validation_password_confirm_label'] = 'Подтверждение пароля';
$lang['create_user_validation_gender']                 = 'Жинси';
$lang['create_user_sender_label']                      = 'Ким юборди:';
$lang['create_user_validation_dob']                    = 'Тугилган сана';
$lang['create_user_validation_doctors_types']          = 'Мутахассислиги';
$lang['create_user_validation_doctor_price']           = 'Қабул нархи';
$lang['create_user_validation_doctor_agreement']       = 'Келишув %';
$lang['create_user_validation_doctor_group']           = 'Гурух (ҳуқуқлар)';
$lang['create_user_validation_address']                = 'Манзил';
$lang['create_user_validation_jobtitle']               = 'Мутахассислиги';
$lang['create_user_validation_department_name']        = 'Бўлим номи';
// Edit User
$lang['edit_user_heading']                           = 'Редактировать пользователя';
$lang['edit_user_subheading']                        = 'Пожалуйста заполните информацию ниже.';
$lang['edit_user_fname_label']                       = 'Имя:';
$lang['edit_user_lname_label']                       = 'Фамилия:';
$lang['edit_user_company_label']                     = 'Название компании:';
$lang['edit_user_email_label']                       = 'Email:';
$lang['edit_user_phone_label']                       = 'Телефон:';
$lang['edit_user_password_label']                    = 'Пароль: (если изменился)';
$lang['edit_user_password_confirm_label']            = 'Подтвердить пароль: (если изменился)';
$lang['edit_user_groups_heading']                    = 'Член группы';
$lang['edit_user_submit_btn']                        = 'Саклаш';
$lang['edit_user_validation_fname_label']            = 'Имя';
$lang['edit_user_validation_lname_label']            = 'Фамилия';
$lang['edit_user_validation_email_label']            = 'Email';
$lang['edit_user_validation_phone1_label']           = 'Первая часть телефона';
$lang['edit_user_validation_phone2_label']           = 'Вторая часть телефона';
$lang['edit_user_validation_phone3_label']           = 'Третья часть телефона';
$lang['edit_user_validation_company_label']          = 'Компания';
$lang['edit_user_validation_groups_label']           = 'Группы';
$lang['edit_user_validation_password_label']         = 'Пароль';
$lang['edit_user_validation_password_confirm_label'] = 'Подтверждение пароля';
$lang['edit_user_validation_jobtitle']               = 'Мутахассислиги';
$lang['edit_user_validation_groups_label']           = 'Гурух';
$lang['edit_user_validation_department_name_label']  = 'Бўлим номи';

// Create Group
$lang['create_group_title']                  = 'Создать группу';
$lang['create_group_heading']                = 'Создать группу';
$lang['create_group_subheading']             = 'Пожалуйста заполните следующую информацию.';
$lang['create_group_name_label']             = 'Группа:';
$lang['create_group_desc_label']             = 'Описание:';
$lang['create_group_submit_btn']             = 'Создать группу';
$lang['create_group_validation_name_label']  = 'Группа';
$lang['create_group_validation_desc_label']  = 'Описание';

// Edit Group
$lang['edit_group_title']                  = 'Редактировать группу';
$lang['edit_group_saved']                  = 'Группа сохранена';
$lang['edit_group_heading']                = 'Редактировать группу';
$lang['edit_group_subheading']             = 'Пожалуйста заполните следующую информацию.';
$lang['edit_group_name_label']             = 'Название группы:';
$lang['edit_group_desc_label']             = 'Описание:';
$lang['edit_group_submit_btn']             = 'Сохранить группу';
$lang['edit_group_validation_name_label']  = 'Группа';
$lang['edit_group_validation_desc_label']  = 'Описание';

// Change Password
$lang['change_password_heading']                               = 'Изменить пароль';
$lang['change_password_old_password_label']                    = 'Старый пароль:';
$lang['change_password_new_password_label']                    = 'Новый пароль (минимум %s символов):';
$lang['change_password_new_password_confirm_label']            = 'Подтвердить пароль:';
$lang['change_password_submit_btn']                            = 'Изменить';
$lang['change_password_validation_old_password_label']         = 'Старый пароль';
$lang['change_password_validation_new_password_label']         = 'Новый пароль';
$lang['change_password_validation_new_password_confirm_label'] = 'Подтвердить пароль';

// Forgot Password
$lang['forgot_password_heading']                 = 'Забыли пароль';
$lang['forgot_password_subheading']              = 'Пожалуйста введите ваш email и мы сможем отправить вам email с новым паролем.';
$lang['forgot_password_email_label']             = '%s:';
$lang['forgot_password_submit_btn']              = 'Отправить';
$lang['forgot_password_validation_email_label']  = 'Email';
$lang['forgot_password_username_identity_label'] = 'Логин';
$lang['forgot_password_email_identity_label']    = 'Email';
$lang['forgot_password_back']    = 'Вернуться';
$lang['forgot_password_email_not_found']         = 'No record of that email address.';
$lang['forgot_password_identity_not_found']         = 'No record of that username address.';

// Reset Password
$lang['reset_password_heading']                               = 'Изменить пароль';
$lang['reset_password_new_password_label']                    = 'Новый пароль (минимум 8 символов):';
$lang['reset_password_new_password_confirm_label']            = 'Подвердить:';
$lang['reset_password_submit_btn']                            = 'Изменить';
$lang['reset_password_validation_new_password_label']         = 'Новый пароль';
$lang['reset_password_validation_new_password_confirm_label'] = 'Подтвердить';

// Activation Email
$lang['email_activate_heading']    = 'Активировать аккаунт для %s';
$lang['email_activate_subheading'] = 'Пожалуйста перейдите по ссылке для %s.';
$lang['email_activate_link']       = 'Активировать аккаунт';

// Forgot Password Email
$lang['email_forgot_password_heading']    = 'Сбросить пароль для %s';
$lang['email_forgot_password_subheading'] = 'Пожалуста по ссылке для %s.';
$lang['email_forgot_password_link']       = 'Сбросить пароль';

$lang['user_cancel_button']   = 'Бекор килиш';

// Shifokor
$lang['doctor_appointment']     = 'Шифокор куриги';
$lang['doctor_laboratories']    = 'Лаборатория';
$lang['doctor_uzi']             = 'УЗИ';
$lang['doctor_save']             = 'Саклаш';

// User Permissions
$lang['user_permission_denied'] = 'Хуқуқингиз етарли эмас';

