<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixForeignKeys extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Dropping
		DB::statement('ALTER TABLE agents DROP INDEX agents_parent_agent_id_index');
		DB::statement('ALTER TABLE check_ins DROP FOREIGN KEY check_ins_installer_id_foreign');
		DB::statement('ALTER TABLE check_ins DROP FOREIGN KEY check_ins_job_id_foreign');
		DB::statement('ALTER TABLE devices DROP FOREIGN KEY devices_user_id_foreign');
		DB::statement('ALTER TABLE job_contacts DROP FOREIGN KEY job_contacts_contact_id_foreign');
		DB::statement('ALTER TABLE job_contacts DROP FOREIGN KEY job_contacts_job_id_foreign');
		DB::statement('ALTER TABLE job_installers DROP FOREIGN KEY job_installers_installer_id_foreign');
		DB::statement('ALTER TABLE job_installers DROP FOREIGN KEY job_installers_job_id_foreign');
		DB::statement('ALTER TABLE job_notifications DROP FOREIGN KEY job_notifications_agent_id_foreign');
		DB::statement('ALTER TABLE job_notifications DROP FOREIGN KEY job_notifications_job_id_foreign');
		DB::statement('ALTER TABLE job_notifications DROP FOREIGN KEY job_notifications_user_id_foreign');
		DB::statement('ALTER TABLE jobs DROP FOREIGN KEY jobs_primary_installer_id_foreign');
		DB::statement('ALTER TABLE push_notification_queue DROP FOREIGN KEY push_notification_queue_device_id_foreign');
		DB::statement('ALTER TABLE quotes DROP FOREIGN KEY quotes_agent_id_foreign');
		DB::statement('ALTER TABLE quotes DROP FOREIGN KEY quotes_client_id_foreign');
		DB::statement('ALTER TABLE quotes DROP FOREIGN KEY quotes_hoarding_type_id_foreign');

		// Fix stuff
		DB::statement('ALTER TABLE clients CHANGE agent_id agent_id INT(10) UNSIGNED NOT NULL');
		DB::statement('UPDATE agents SET parent_agent_id = NULL WHERE parent_agent_id = 0');
		DB::statement('UPDATE jobs SET related_job_id = id WHERE related_job_id = 0');
		DB::statement('UPDATE jobs j LEFT JOIN users u ON j.primary_installer_id = u.id SET j.primary_installer_id = NULL WHERE u.id IS NULL');
		DB::statement('UPDATE jobs SET material_id = (SELECT id FROM hoarding_materials LIMIT 1) WHERE material_id = 0');
		DB::statement('UPDATE users u LEFT JOIN agents a ON u.agent_id = a.id SET u.agent_id = NULL WHERE a.id IS NULL');
		DB::statement('DELETE i.* FROM images i LEFT JOIN users u ON i.user_id = u.id WHERE u.id IS NULL');
		DB::statement('DELETE i.* FROM invitations i LEFT JOIN users u ON i.user_id = u.id WHERE u.id IS NULL');
		DB::statement('DELETE j.* FROM jobs j LEFT JOIN users u ON j.user_id = u.id WHERE u.id IS NULL');
		DB::statement('DELETE jn.* FROM job_notifications jn LEFT JOIN users u ON jn.user_id = u.id WHERE u.id IS NULL');
		DB::statement('DELETE d.* FROM devices d LEFT JOIN users u ON d.user_id = u.id WHERE u.id IS NULL');
		DB::statement('DELETE ci.* FROM check_ins ci LEFT JOIN users u ON ci.installer_id = u.id WHERE u.id IS NULL');
		DB::statement('DELETE ai.* FROM agent_installers ai LEFT JOIN agents a ON ai.agent_id = a.id WHERE a.id IS NULL');
		DB::statement('DELETE c.* FROM clients c LEFT JOIN agents a ON c.agent_id = a.id WHERE a.id IS NULL');
		DB::statement('DELETE c.* FROM contacts c LEFT JOIN agents a ON c.agent_id = a.id WHERE a.id IS NULL');
		DB::statement('DELETE q.* FROM push_notification_queue q LEFT JOIN devices d ON q.device_id = d.id WHERE d.id IS NULL');
		DB::statement('DELETE q.* FROM form_questions q LEFT JOIN form_categories c ON q.category_id = c.id WHERE c.id IS NULL');
		DB::statement('DELETE j.* FROM jobs j LEFT JOIN clients c ON j.client_id = c.id WHERE c.id IS NULL');
		DB::statement('DELETE a.* FROM form_answers a LEFT JOIN jobs j ON a.job_id = j.id WHERE j.id IS NULL');
		DB::statement('DELETE jc.* FROM job_contacts jc LEFT JOIN jobs j ON jc.job_id = j.id WHERE j.id IS NULL');
		DB::statement('DELETE jd.* FROM job_documents jd LEFT JOIN jobs j ON jd.job_id = j.id WHERE j.id IS NULL');
		DB::statement('DELETE jn.* FROM job_notifications jn LEFT JOIN jobs j ON jn.job_id = j.id WHERE j.id IS NULL');
		DB::statement('DELETE ji.* FROM job_installers ji LEFT JOIN jobs j ON ji.job_id = j.id WHERE j.id IS NULL');
		DB::statement('DELETE jn.* FROM job_notes jn LEFT JOIN jobs j ON jn.job_id = j.id WHERE j.id IS NULL');
		DB::statement('DELETE jp.* FROM job_products jp LEFT JOIN jobs j ON jp.job_id = j.id WHERE j.id IS NULL');
		DB::statement('DELETE ci.* FROM check_ins ci LEFT JOIN jobs j ON ci.job_id = j.id WHERE j.id IS NULL');
		DB::statement('DELETE a.* FROM form_answers a LEFT JOIN form_questions q ON a.question_id = q.id WHERE q.id IS NULL');
		DB::statement('DELETE i.* FROM images i LEFT JOIN jobs j ON i.job_id = j.id WHERE j.id IS NULL');

		// Adding
		DB::statement('ALTER TABLE agents ADD CONSTRAINT agents_parent_agent_id_foreign FOREIGN KEY (parent_agent_id) REFERENCES agents(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE agent_installers ADD CONSTRAINT agent_installers_agent_id_foreign FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE agent_installers ADD CONSTRAINT agent_installers_installer_id_foreign FOREIGN KEY (installer_id) REFERENCES users(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE certifications ADD CONSTRAINT certifications_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE check_ins ADD CONSTRAINT check_ins_installer_id_foreign FOREIGN KEY (installer_id) REFERENCES users(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE check_ins ADD CONSTRAINT check_ins_job_id_foreign FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE clients ADD CONSTRAINT clients_agent_id_foreign FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE contacts ADD CONSTRAINT contacts_agent_id_foreign FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE contacts ADD CONSTRAINT contacts_client_id_foreign FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL');
		DB::statement('ALTER TABLE devices ADD CONSTRAINT devices_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE document_fields ADD CONSTRAINT document_fields_document_id_foreign FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE document_submissions ADD CONSTRAINT document_submissions_document_id_foreign FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE document_submissions ADD CONSTRAINT document_submissions_job_id_foreign FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE document_submissions ADD CONSTRAINT document_submissions_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE document_submission_values ADD CONSTRAINT document_submission_values_submission_id_foreign FOREIGN KEY (submission_id) REFERENCES document_submissions(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE document_submission_values ADD CONSTRAINT document_submission_values_field_id_foreign FOREIGN KEY (field_id) REFERENCES document_fields(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE form_answers ADD CONSTRAINT form_answers_job_id_foreign FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE form_answers ADD CONSTRAINT form_answers_question_id_foreign FOREIGN KEY (question_id) REFERENCES form_questions(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE form_questions ADD CONSTRAINT form_questions_category_id_foreign FOREIGN KEY (category_id) REFERENCES form_categories(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE images ADD CONSTRAINT images_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE images ADD CONSTRAINT images_job_id_foreign FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE installer_certifications ADD CONSTRAINT installer_certifications_installer_id_foreign FOREIGN KEY (installer_id) REFERENCES users(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE installer_certifications ADD CONSTRAINT installer_certifications_certification_id_foreign FOREIGN KEY (certification_id) REFERENCES certifications(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE invitations ADD CONSTRAINT invitations_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE jobs ADD CONSTRAINT jobs_agent_id_foreign FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE jobs ADD CONSTRAINT jobs_client_id_foreign FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE jobs ADD CONSTRAINT jobs_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE jobs ADD CONSTRAINT jobs_primary_installer_id_foreign FOREIGN KEY (primary_installer_id) REFERENCES users(id) ON DELETE SET NULL');
		DB::statement('ALTER TABLE jobs ADD CONSTRAINT jobs_related_job_id_foreign FOREIGN KEY (related_job_id) REFERENCES jobs(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE jobs ADD CONSTRAINT jobs_hoarding_type_id_foreign FOREIGN KEY (hoarding_type_id) REFERENCES hoarding_types(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE jobs ADD CONSTRAINT jobs_material_id_foreign FOREIGN KEY (material_id) REFERENCES hoarding_materials(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE job_contacts ADD CONSTRAINT job_contacts_job_id_foreign FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE job_contacts ADD CONSTRAINT job_contacts_contact_id_foreign FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE job_documents ADD CONSTRAINT job_documents_job_id_foreign FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE job_installers ADD CONSTRAINT job_installers_job_id_foreign FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE job_installers ADD CONSTRAINT job_installers_installer_id_foreign FOREIGN KEY (installer_id) REFERENCES users(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE job_notes ADD CONSTRAINT job_notes_job_id_foreign FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE job_notes ADD CONSTRAINT job_notes_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE job_notifications ADD CONSTRAINT job_notifications_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE job_notifications ADD CONSTRAINT job_notifications_agent_id_foreign FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE job_notifications ADD CONSTRAINT job_notifications_job_id_foreign FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE job_products ADD CONSTRAINT job_products_job_id_foreign FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE job_products ADD CONSTRAINT job_products_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE product_prices ADD CONSTRAINT product_prices_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE product_prices ADD CONSTRAINT product_prices_agent_id_foreign FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE product_resources ADD CONSTRAINT product_resources_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE product_resources ADD CONSTRAINT product_resources_resource_id_foreign FOREIGN KEY (resource_id) REFERENCES resources(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE push_notification_queue ADD CONSTRAINT push_notification_queue_device_id_foreign FOREIGN KEY (device_id) REFERENCES devices(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE resource_views ADD CONSTRAINT resource_views_resource_id_foreign FOREIGN KEY (resource_id) REFERENCES resources(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE resource_views ADD CONSTRAINT resource_views_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
		DB::statement('ALTER TABLE users ADD CONSTRAINT users_agent_id_foreign FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE SET NULL');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Dropping
		DB::statement('ALTER TABLE agents DROP FOREIGN KEY agents_parent_agent_id_foreign');
		DB::statement('ALTER TABLE agent_installers DROP FOREIGN KEY agent_installers_agent_id_foreign');
		DB::statement('ALTER TABLE agent_installers DROP FOREIGN KEY agent_installers_installer_id_foreign');
		DB::statement('ALTER TABLE certifications DROP FOREIGN KEY certifications_product_id_foreign');
		DB::statement('ALTER TABLE check_ins DROP FOREIGN KEY check_ins_installer_id_foreign');
		DB::statement('ALTER TABLE check_ins DROP FOREIGN KEY check_ins_job_id_foreign');
		DB::statement('ALTER TABLE clients DROP FOREIGN KEY clients_agent_id_foreign');
		DB::statement('ALTER TABLE contacts DROP FOREIGN KEY contacts_agent_id_foreign');
		DB::statement('ALTER TABLE contacts DROP FOREIGN KEY contacts_client_id_foreign');
		DB::statement('ALTER TABLE devices DROP FOREIGN KEY devices_user_id_foreign');
		DB::statement('ALTER TABLE document_fields DROP FOREIGN KEY document_fields_document_id_foreign');
		DB::statement('ALTER TABLE document_submissions DROP FOREIGN KEY document_submissions_document_id_foreign');
		DB::statement('ALTER TABLE document_submissions DROP FOREIGN KEY document_submissions_job_id_foreign');
		DB::statement('ALTER TABLE document_submissions DROP FOREIGN KEY document_submissions_user_id_foreign');
		DB::statement('ALTER TABLE document_submission_values DROP FOREIGN KEY document_submission_values_submission_id_foreign');
		DB::statement('ALTER TABLE document_submission_values DROP FOREIGN KEY document_submission_values_field_id_foreign');
		DB::statement('ALTER TABLE form_answers DROP FOREIGN KEY form_answers_job_id_foreign');
		DB::statement('ALTER TABLE form_answers DROP FOREIGN KEY form_answers_question_id_foreign');
		DB::statement('ALTER TABLE form_questions DROP FOREIGN KEY form_questions_category_id_foreign');
		DB::statement('ALTER TABLE images DROP FOREIGN KEY images_user_id_foreign');
		DB::statement('ALTER TABLE images DROP FOREIGN KEY images_job_id_foreign');
		DB::statement('ALTER TABLE installer_certifications DROP FOREIGN KEY installer_certifications_installer_id_foreign');
		DB::statement('ALTER TABLE installer_certifications DROP FOREIGN KEY installer_certifications_certification_id_foreign');
		DB::statement('ALTER TABLE invitations DROP FOREIGN KEY invitations_user_id_foreign');
		DB::statement('ALTER TABLE jobs DROP FOREIGN KEY jobs_agent_id_foreign');
		DB::statement('ALTER TABLE jobs DROP FOREIGN KEY jobs_client_id_foreign');
		DB::statement('ALTER TABLE jobs DROP FOREIGN KEY jobs_user_id_foreign');
		DB::statement('ALTER TABLE jobs DROP FOREIGN KEY jobs_primary_installer_id_foreign');
		DB::statement('ALTER TABLE jobs DROP FOREIGN KEY jobs_related_job_id_foreign');
		DB::statement('ALTER TABLE jobs DROP FOREIGN KEY jobs_hoarding_type_id_foreign');
		DB::statement('ALTER TABLE jobs DROP FOREIGN KEY jobs_material_id_foreign');
		DB::statement('ALTER TABLE job_contacts DROP FOREIGN KEY job_contacts_job_id_foreign');
		DB::statement('ALTER TABLE job_contacts DROP FOREIGN KEY job_contacts_contact_id_foreign');
		DB::statement('ALTER TABLE job_documents DROP FOREIGN KEY job_documents_job_id_foreign');
		DB::statement('ALTER TABLE job_installers DROP FOREIGN KEY job_installers_job_id_foreign');
		DB::statement('ALTER TABLE job_installers DROP FOREIGN KEY job_installers_installer_id_foreign');
		DB::statement('ALTER TABLE job_notes DROP FOREIGN KEY job_notes_job_id_foreign');
		DB::statement('ALTER TABLE job_notes DROP FOREIGN KEY job_notes_user_id_foreign');
		DB::statement('ALTER TABLE job_notifications DROP FOREIGN KEY job_notifications_user_id_foreign');
		DB::statement('ALTER TABLE job_notifications DROP FOREIGN KEY job_notifications_agent_id_foreign');
		DB::statement('ALTER TABLE job_notifications DROP FOREIGN KEY job_notifications_job_id_foreign');
		DB::statement('ALTER TABLE job_products DROP FOREIGN KEY job_products_job_id_foreign');
		DB::statement('ALTER TABLE job_products DROP FOREIGN KEY job_products_product_id_foreign');
		DB::statement('ALTER TABLE product_prices DROP FOREIGN KEY product_prices_product_id_foreign');
		DB::statement('ALTER TABLE product_prices DROP FOREIGN KEY product_prices_agent_id_foreign');
		DB::statement('ALTER TABLE product_resources DROP FOREIGN KEY product_resources_product_id_foreign');
		DB::statement('ALTER TABLE product_resources DROP FOREIGN KEY product_resources_resource_id_foreign');
		DB::statement('ALTER TABLE push_notification_queue DROP FOREIGN KEY push_notification_queue_device_id_foreign');
		DB::statement('ALTER TABLE resource_views DROP FOREIGN KEY resource_views_resource_id_foreign');
		DB::statement('ALTER TABLE resource_views DROP FOREIGN KEY resource_views_user_id_foreign');
		DB::statement('ALTER TABLE users DROP FOREIGN KEY users_agent_id_foreign');

		// Undo fixing of stuff
		DB::statement('ALTER TABLE clients CHANGE agent_id agent_id INT(11) NOT NULL');

		// Adding
		DB::statement('ALTER TABLE agents ADD INDEX agents_parent_agent_id_index (parent_agent_id)');
		DB::statement('ALTER TABLE check_ins ADD CONSTRAINT check_ins_installer_id_foreign FOREIGN KEY (installer_id) REFERENCES users(id)');
		DB::statement('ALTER TABLE check_ins ADD CONSTRAINT check_ins_job_id_foreign FOREIGN KEY (job_id) REFERENCES jobs(id)');
		DB::statement('ALTER TABLE devices ADD CONSTRAINT devices_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id)');
		DB::statement('ALTER TABLE job_contacts ADD CONSTRAINT job_contacts_contact_id_foreign FOREIGN KEY (contact_id) REFERENCES contacts(id)');
		DB::statement('ALTER TABLE job_contacts ADD CONSTRAINT job_contacts_job_id_foreign FOREIGN KEY (job_id) REFERENCES jobs(id)');
		DB::statement('ALTER TABLE job_installers ADD CONSTRAINT job_installers_installer_id_foreign FOREIGN KEY (installer_id) REFERENCES users(id)');
		DB::statement('ALTER TABLE job_installers ADD CONSTRAINT job_installers_job_id_foreign FOREIGN KEY (job_id) REFERENCES jobs(id)');
		DB::statement('ALTER TABLE job_notifications ADD CONSTRAINT job_notifications_agent_id_foreign FOREIGN KEY (agent_id) REFERENCES agents(id)');
		DB::statement('ALTER TABLE job_notifications ADD CONSTRAINT job_notifications_job_id_foreign FOREIGN KEY (job_id) REFERENCES jobs(id)');
		DB::statement('ALTER TABLE job_notifications ADD CONSTRAINT job_notifications_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id)');
		DB::statement('ALTER TABLE jobs ADD CONSTRAINT jobs_primary_installer_id_foreign FOREIGN KEY (primary_installer_id) REFERENCES users(id)');
		DB::statement('ALTER TABLE push_notification_queue ADD CONSTRAINT push_notification_queue_device_id_foreign FOREIGN KEY (device_id) REFERENCES devices(id)');
		DB::statement('ALTER TABLE quotes ADD CONSTRAINT quotes_agent_id_foreign FOREIGN KEY (agent_id) REFERENCES agents(id)');
		DB::statement('ALTER TABLE quotes ADD CONSTRAINT quotes_client_id_foreign FOREIGN KEY (client_id) REFERENCES clients(id)');
		DB::statement('ALTER TABLE quotes ADD CONSTRAINT quotes_hoarding_type_id_foreign FOREIGN KEY (hoarding_type_id) REFERENCES hoarding_types(id)');
	}

}
