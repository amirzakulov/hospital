$(document).ready(function () {

    $(document).on('click','.printBtn', function(){
        $(document).find('#printArea').printElement({
            title:'HospitaZM',
            css: 'extend',
            ecss:'',
            keepHide: [".printBtn", ".zmlogo", ".hideElement"],

        });
    });

    if($(".rich_text_content").length > 0) {
        $("#patient_complaint").richText();
        $("#anamnesis_morbi").richText();
        $("#anamnesis_vitae").richText();
        $("#status_praesens").richText();
        $("#description").richText();
        $("#diagnosis").richText();
        $(".rich_text_content").richText();
    }

	if($("#template").length > 0) {
		// $("#template").richText({
		// 	fonts: true,
		// 	fontSize: true,
		// 	videoEmbed: false
		// });
		//
		// $("#template_ru").richText({
		// 	fonts: true,
		// 	fontSize: true,
		// 	videoEmbed: false
		// });

		$("#template").summernote({
			placeholder: 'Текст',
			tabsize: 2,
			height: 400,
			toolbar: [
				['font', ['bold', 'underline','italic']],
				['font', ['clear']],
				['table', ['table']],
				// ['fontsize', ['fontsize']],
				['view', ['codeview']],
			]
		});
		$("#template_ru").summernote({
			placeholder: 'Текст',
			tabsize: 2,
			height: 400,
			toolbar: [
				['font', ['bold', 'underline','italic']],
				['font', ['clear']],
				['table', ['table']],
				// ['fontsize', ['fontsize']],
				['fontSizeUnits', ['px']],
				['view', ['codeview']],
			]
		});
	}

	if($("#patient_uzi_page").length > 0) {
		let $this = $("#patient_uzi_page");
		let payment_id = $this.data("paymentId");
		let url = $this.data("url");

		$.post(url, {payment_id:payment_id}, function (uzi_ids) {
			$.each(uzi_ids, function (index, uzi_id) {
				$(".uzi_result_"+uzi_id).summernote({
					placeholder: 'Текст',
					tabsize: 2,
					height: 400,
					toolbar: [
						['font', ['bold', 'underline','italic']],
						['font', ['clear']],
						['table', ['table']],
						// ['fontsize', ['fontsize']],
						['fontSizeUnits', ['px']],
						['view', ['codeview']],
					]
				});
			});

			$(".uzi_conclusion").summernote({
				placeholder: 'текст',
				tabsize: 2,
				height: 400,
				toolbar: [
					['font', ['bold', 'underline','italic']],
					['font', ['clear']],
					['table', ['table']],
					// ['fontsize', ['fontsize']],
					['fontSizeUnits', ['px']],
					['view', ['codeview']],

				]
			});
		}, "json");
	}

    var $loading = $('#loadingDiv').hide();
    $(document)
        .ajaxStart(function () {
            setTimeout(function () {
                $loading.show();
            }, 1000)
        })
        .ajaxStop(function () {
            setTimeout(function () {
                $loading.hide();
            }, 1000)
        });

    if($(".js_diagnosis_result #list-tab").length > 0) {
		$(".js_diagnosis_result #list-tab").sticky({topSpacing:70});
	}

	if($(".form-control-chosen").length > 0) {
		$('.form-control-chosen').chosen({});
	}


    $(".js_patient_diagnos_save").on("submit", function (e) {
        var $f   = $(this);
        var data = $f.serializeArray();

        var $submit_btn             = $f.find(".js_patient_diagnos_save_btn");
        var patient_history_btns    = $f.find(".js_patient_history_btns");
        e.preventDefault();

        var url = $f.attr("action");//doctor/patients/ajax_patient_diagnos_save
        $.post(url, data, function (res) {
            if(res["error"]) {
                $f.find(".js_patient_complaint_validation_message").removeClass("d-none").find(".js_warning_text").html(res["message"]);
                $f.find(".invalid-feedback").html(res["message"]).show();
            } else {
                $(".js_diagnosis_result").html(res.html);

                $f.find(".js_patient_complaint_text").text(res.data["patient_complaint"]);
                $f.find(".js_anamnesis_morbi_text").text(res.data["anamnesis_morbi"]);
                $f.find(".js_anamnesis_vitae_text").text(res.data["anamnesis_vitae"]);
                $f.find(".js_status_praesens_text").text(res.data["status_praesens"]);
                $f.find(".js_description_text").text(res.data["description"]);
                $f.find(".js_diagnosiz_text").text(res.data["diagnosis"]);

                $f.find(".js_patient_complaint_validation_message").addClass("d-none").find(".js_warning_text").html("");
                $f.find(".invalid-feedback").html(res["message"]).hide();
                $f.find(".js_diagnosis_text").removeClass("d-none");
                $f.find(".js_diagnosis_input").addClass("d-none");
                $submit_btn.addClass("d-none");
                $f.find(".js_cancel_patient_history").addClass("d-none");
                $f.find(".js_edit_patient_history").removeClass("d-none");

                patient_history_btns.removeClass("d-none");

            }
        }, "json");
    });


    /************************************************/
    $(".js_patient_doctor_status").click(function () {
        let $this = $(this);
		let parent_div = $this.parent("div");
		let url = parent_div.data("url"); // url = "/doctor/patients/ajax_patient_doctor_status"
		let payment_id = parent_div.data("paymentId");
		let qabul_tamom_btn = parent_div.find("#qabul_tamom");
		let doctor_status = $this.attr("id");
		let patient_doctor_id = $this.data("patientDoctorId");
		let form = $("form");

        $.post(url, {"payment_id":payment_id, doctor_status:doctor_status, patient_doctor_id:patient_doctor_id}, function (doc_status) {
            parent_div.find("button").addClass("d-none");
            if(doc_status == "qabulda") {
                qabul_tamom_btn.removeClass("d-none");
                form.find("input").prop("disabled", false);
                form.find("button").prop("disabled", false);
            } else if(doc_status == "qabul_tamom") {
                window.location.replace($this.data("backtourl"));
            }
        }, "json");
    });
    /************************************************/

    $(".js_patient_laboratory_status").click(function () {
        var $this = $(this);
        var url = $this.parent("div").data("url"); // url = "/doctor/patients_lab/ajax_patient_laboratory_status"
        var payment_id = $this.parent("div").data("paymentId");
        var parent_div = $this.parent("div");
        var qabul_tamom_btn = parent_div.find("#qabul_tamom");
        var natija_tayyor_btn = parent_div.find("#natija_tayyor");
        var laboratory_status = $this.attr("id");
        $.post(url, {"payment_id":payment_id, laboratory_status:laboratory_status}, function (lab_status) {
            parent_div.find("button").addClass("d-none");
            if(lab_status == "qabulda") {
                qabul_tamom_btn.removeClass("d-none");
				$(".js_files_to_upload").attr("disabled", false);
				$(".js_save_all_lab_results").attr("disabled", false);
                var inputs = $("input[name='result[]']");
                $.each(inputs, function (index, inp) {
                    $(inp).attr("disabled", false); //inputlardan disabledni olib tashlaymiz
                    $(".js_tab_content").find("span.input-group-text").addClass("js_accept_lab_result"); //saqlash buttonlarini active qilib quyamiz
                });

			} else if(lab_status == "qabul_tamom") {
                natija_tayyor_btn.removeClass("d-none");
            } else if(lab_status == "natija_tayyor") {
                window.location.replace($this.data("backtourl"));
            } else {
                natija_tayyor_btn.removeClass("d-none");
                var nodelete_notifier_model = $("#nodelete_notifier");
                nodelete_notifier_model.find(".modal-body").text("Buyurtmani tamomlash uchun, avval, har-bir laboratoriyaning natijalarini to'ldirishingiz kerak!");
                $("#nodelete_notifier").modal("show");
            }

        }, "json");
    });

    $(".js_edit_patient_history").click(function () {
        var $this = $(this);
        var patient_history_form = $this.closest("form");
        patient_history_form.find(".js_diagnosis_text").addClass("d-none");
        patient_history_form.find(".js_diagnosis_input").removeClass("d-none");
        $this.addClass("d-none");
        $(".js_cancel_patient_history").removeClass("d-none");
        patient_history_form.find(".js_patient_diagnos_save_btn").removeClass("d-none");
    });

    $(".js_cancel_patient_history").click(function () {
        var $this = $(this);
        var patient_history_form = $this.closest("form");
        patient_history_form.find(".js_diagnosis_text").removeClass("d-none");
        patient_history_form.find(".js_diagnosis_input").addClass("d-none");
        $this.addClass("d-none");
        $(".js_edit_patient_history").removeClass("d-none");
        patient_history_form.find(".js_patient_diagnos_save_btn").addClass("d-none");
    });

    $(document).on("click", ".js_accept_lab_result", function () {

        var $this = $(this);
        var payment_id = $this.closest(".js_diagnosis_result").data("paymentId");
        var tab_content = $this.closest(".js_tab_content");
        var input_group = $this.closest(".input-group");
        var url = tab_content.data("url");
        var result = input_group.find("input").val();
        var lp_id = input_group.find("input").attr("id");
        var precategory = input_group.find("input").data("precategory");

        $.post(url, {lp_id:lp_id, result:result, "type":"single"}, function (result) {
            if(result.res == true) {
                $this.addClass("bg-success");

                if(result.completed == true) {
                    $("#list-"+precategory+"-list").find("span.fa").removeClass("fa-circle-o").addClass("fa-check-square");
                }
            } else {
                var nodelete_notifier_model = $("#nodelete_notifier");
                nodelete_notifier_model.find(".modal-body").text("Buyurtmani tamomlash uchun, avval, har-bir laboratoriyaning natijalarini to'ldirishingiz kerak!");
                $("#nodelete_notifier").modal("show");
            }
        }, "json");
    });

	//Uzi natijasini saqlash
	$(".js_save_uzi_result").click(function (e) {
		e.preventDefault();
		let $this = $(this);
		let form = $this.closest("form");
		let form_data = new FormData();
		let url = form.attr("action");//doctor/patients_uzi/ajax_save_uzi_result
		let lang = form.find("[name='lang']").val();

		form_data.append("lang", lang);

		//result larni olamiz
		let textareas = $("textarea[name='result[]']");
		let textarea_data = [];

		$.each(textareas, function (index, tarea) {
			let idd = $(tarea).attr("id");
			let val = $(tarea).val();
			form_data.append("result["+idd+"]", val);
		});

		let uzi_conclusion_dom = $("textarea[name='uzi_conclusion']");
		let uzi_conclusion_result 	= uzi_conclusion_dom.val();
		let uzi_conclusion_id 		= uzi_conclusion_dom.attr("id");

		form_data.append("uzi_conclusion_result", uzi_conclusion_result);
		form_data.append("uzi_conclusion_id", uzi_conclusion_id);

		$.ajax(
			{
				url: url,
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				type: 'POST',
				success: function (res) {
					if(res.updated) {
						let $modal = $("#notifier");

						$modal.find(".modal-body").html("Маълумотлар муваффақиятли сақланди!")
						$modal.modal("show");
						setTimeout(function () {
							$modal.modal("hide")
						}, "1500")
					}
				},
				error: function (response) {
					$('#msg').html(response); // display error response from the server
				}
			}
		);

	});


	$(".js_save_all_lab_results").click(function (e) {
        e.preventDefault();
        let $this = $(this);
        let form_data = new FormData();
        let tab_content = $this.closest(".js_tab_content");
        let url = tab_content.data("url");//doctor/patients_lab/ajax_lab_result_save
		let form = $this.closest("form");
		let payment_id = form.find("[name='payment_id']").val();
		form_data.append("payment_id", payment_id);

        form_data.append("type", "multiple");
        //result larni olamiz
        let inputs 			= $("input[name='result[]']");
		let input_data 		= [];
		let data_by_parent 	= [];
		let precategory_id 	= 0;
		let is_parent_id 	= null;

        $.each(inputs, function (index, inp) {
        	is_parent_id 	= $(inp).data("isParentId");
			precategory_id 	= $(inp).data("precategory");

			let parent_id 	= (is_parent_id == "") ? precategory_id : is_parent_id;
        	let idd = $(inp).attr("id");
            var val = $(inp).val();
            form_data.append("result["+idd+"]", val);

			let checkbox = $("#recommendation_"+idd).prop('checked') ? 1:0;
            form_data.append("recommendation["+idd+"]", checkbox);

			data_by_parent.push({"parent_id": parent_id, idd:idd, val:val})

        });

        let is_parents = [];
        $.each(data_by_parent, function (index, lab) {
			// is_parents[lab.parent_id] = 1;
			form_data.append("is_parents["+lab.parent_id+"]", 1);
		});

		$.each(data_by_parent, function (index, lab) {
			if(lab.val == '') {
				// is_parents[lab.parent_id] = 0;
				form_data.append("is_parents["+lab.parent_id+"]", 0);
			}
		});

		let iFiles = $("input[name='lab_shots[]']");
        for (var x = 0; x < iFiles.length; x++) {
            var xFiles = iFiles[x].files;
            var xFileId = iFiles[x].id;
            for (var y = 0; y < xFiles.length; y++) {
                form_data.append('lab_shots['+xFileId+'][]', xFiles[y]);
            }
        }

		$.ajax(
            {
                url: url,
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'POST',
                success: function (res) {
                    var obj = JSON.parse(res);
                    if(obj.success){
                        var $modal = $("#notifier");

                        $modal.find(".modal-body").html("Маълумотлар муваффақиятли сақланди!")
                        $modal.modal("show");
                        setTimeout(function () {
                            $modal.modal("hide")
                        }, "800")
                    }

                    // console.log(obj.incompled_labs);
					$(".lab_result_finish").find("span").removeClass("fa-circle-o").addClass("fa-check-square");
					$(".lab_result_input_block").find(".js_sub_laboratory_error").html("");

					if(obj.incompled_labs.length != 0) {

						$.each(obj.incompled_labs, function (lab_parent_id, patient_sublabs) {
							$("#list-" + lab_parent_id + "-list").find("span.fa").removeClass("fa-check-square").addClass("fa-circle-o");

							$.each(patient_sublabs, function (index, patient_lab_id) {
								$(".lab_result_input_block").find(".js_sub_laboratory_error_"+patient_lab_id).html("Тўлдириш мажбурий");
							})


						})
					}

                },
                error: function (response) {
                    $('#msg').html(response); // display error response from the server
                }
            }
        );

    });

	//laboratoriyani printerdan chiqarishdan oldin kurish
	$(".js_pre_print").click(function () {

		var $this = $(this);
		var url = $this.data("url");
		var payment_id = $this.data("paymentId");
		var payment_date = $this.data("paymentDate");
		var pre_print_modal = $("#pre_print");
		$.post(url, {payment_id: payment_id, payment_date:payment_date}, function (res) {
			pre_print_modal.find(".modal-body").html(res);
			pre_print_modal.modal("show");
		}, "json");
	});

	//laboratoriyani printerdan chiqarishdan oldin kurish
	$(".js_uzi_pre_print").click(function () {

		let $this 			= $(this);
		let url 			= $this.data("url");//doctor/patients_uzi/ajax_print_preview2
		let payment_id 		= $this.data("paymentId");
		// let payment_date 	= $this.data("paymentDate");
		let pre_print_modal = $("#pre_print_uzi");
		$.post(url, {payment_id: payment_id}, function (res) {
			pre_print_modal.find(".modal-body").html(res);
			pre_print_modal.modal("show");
		}, "json");

	});

    /**
     * Bemorni uchirish. Delete tugmasini bosilganda, patient ga tegishli bulgan barcha ma'lumotlar uchadi
     * doctor kurigi, laboratoriya analizlari, UZI analizlari va bemorning uzi xaqidagi ma'lumotlar ham.
     * **/
    $(".js_delele_item").click(function () {
        var $this = $(this);
        var url = $this.data("href");
        var id = $this.data("id");
        var message = "Ушбу маълумотни хақиқатан хам ўчирасизми?";
        show_notification_modal(url, id, false, message);
    });

    //notification modal
    function show_notification_modal(url, patient_id, payment_id, message) {
        var $modal = $("#confirm_delete_notifier");
        var yes_btn = $modal.find(".modal-footer .js_yes");
        yes_btn.data("url", url);
        yes_btn.data("payment_id", payment_id);
        yes_btn.data("patient_id", patient_id);
        var text = "<p><i class='fa fa-remove fa-2x text-danger'></i> "+message+"</p>";
        $modal.find(".modal-body").html(text);
        $modal.modal("show");
    }

    $(document).on("click", ".js_yes", function () {
        var $this = $(this);
        var url = $this.data("url");
        var patient_id = $this.data("patient_id");
        var payment_id = $this.data("payment_id");
        var $modal = $this.closest("#confirm_delete_notifier");
        var row = $("#js_row_"+patient_id);
        var text = "";

        $.post(url, {id:patient_id, payment_id:payment_id}, function (res) {
            if(res.action == "deleted") {
                text = "<i class='fa fa-info-circle fa-2x text-primary'></i> Маълумот муваффақиятли ўчирилди!";
                $modal.find(".modal-body").html(text);
                $modal.modal("hide");
                row.remove();
            } else if(res.action == "canceled") {
                $modal.find(".modal-body").html("");
                $modal.modal("hide");
                row.remove();
            }
        }, "json");
    });

    if($('.datetimepicker-salary').length > 0) {
        $('.datetimepicker-salary').datetimepicker({
            format: 'DD.MM.YYYY',

        });
    }

    $(".js_files_to_upload").change(function (e) {
        var $this = $(this);
        var file_wrapper = $this.closest("div");
        var file_label = file_wrapper.find(".js_files_to_upload_label");
        var selected_files = "";
        $.each(e.target.files, function (index, value) {
            var file_name = value.name;
            file_name = file_name.substr(0, 15);
            selected_files +=file_name+", ";
        });

        file_label.html(selected_files);

    });

    $(".js_download_templates").click(function () {
		let $this = $(this);
		let url = $this.data("url");
		$.post(url, {}, function (res) {
			window.location.reload();
		});
	});

	$(".js_template_uzi_lang").click(function () {

		let $this 		= $(this);
		let lang 		= $this.data("lang");
		let payment_id 	= $this.parent().data("paymentId");
		let url 		= $this.parent().data("url");
		let content 	= $this.closest("#patient_uzi_page");
		let form 		= content.find("form");
		let lang_field 	= form.find("[name='lang']");

		let buttons 	= $this.closest(".js_uzi_buttons_block");
		let pdf_btn 	= buttons.find(".js_generate_pdf_btn");
		let pdf_btn_url	= pdf_btn.attr("href").slice(0, -3);
		let lang_id 	= lang == "ru" ? 2:1;

		pdf_btn.attr("href", pdf_btn_url+"/"+lang_id+"/");

		lang_field.val(lang);

		$this.parent("div").find(".js_template_uzi_lang").removeClass("active");
		$this.addClass("active");
		
		$.post(url, {lang: lang, payment_id:payment_id}, function (res) {
			let templates = res.templates;

			// $.each(templates, function (uzi_id, template) {
			// 	form.find("[data-uzi-id=" + uzi_id + "]").summernote('reset');
			// });

			$.each(templates, function (uzi_id, template) {
				form.find("[data-uzi-id=" + uzi_id + "]").summernote('pasteHTML', template.result);
			});

			form.find(".uzi_conclusion").val("").trigger("change");

		}, "json");
	});


	$('.js_tabInput').keypress(function(e){
		let $this = $(this);
		var thisElIndex = $this.index(".js_tabInput");
		var keycode = (e.keyCode ? e.keyCode : e.which);
		if(keycode == '13'){
			e.preventDefault();
			$('.js_tabInput').eq(thisElIndex+1).focus().select();
			// $('.js_tabInput').eq(thisElIndex+1).select();
		}
	});

	$('.js_tabInput').keydown(function (e) {
		let $this = $(this);
		var thisElIndex = $this.index(".js_tabInput");
		let inputs = $(".js_tabInput");
		let tabInputMaxIndex = inputs.length - 1;

		if(thisElIndex == tabInputMaxIndex) {
			thisElIndex = -1;
		}

		let arrow = { left: 37, up: 38, right: 39, down: 40 };
		switch (e.which) {
			case arrow.up:
				console.log("up");
				$('.js_tabInput').eq(thisElIndex-1).focus().select();
				break;
			case arrow.down:
				console.log("down");
				$('.js_tabInput').eq(thisElIndex+1).focus().select();
				break;
		}
	});

	if($(document).find(".lab_dashboard_dt").length > 0) {
		create_lab_result_datatable();
	}

	$(".js_lab_division").click(function () {
		let $this = $(this);
		let pl_results = $this.closest("#pl_results");
		let results_table = pl_results.find("#pl_results_table");
		let division_id = $this.data("divisionId");
		let url = $this.data("url");

		let lab_date_range_block_dom = $(".js_lab_date_range");
		let start_date_dom 	= lab_date_range_block_dom.find(".js_start_date");
		let start_date 		= start_date_dom.val();
		let end_date_dom 	= lab_date_range_block_dom.find(".js_end_date");
		let end_date 		= end_date_dom.val();

		$this.closest("ul").find(".js_lab_division").removeClass("active");
		$this.addClass("active");

		$.post(url, {division_id: division_id, start_date:start_date, end_date:end_date}, function (html) {
			results_table.html(html);
			create_lab_result_datatable();
		}, "json");
	});

	$(".js_lab_date_range_submit").click(function () {
		let $this = $(this);
		let lab_date_range_block_dom = $this.closest(".js_lab_date_range");
		let start_date_dom 	= lab_date_range_block_dom.find("input[type=text].js_start_date");
		let start_date 		= start_date_dom.val();
		let end_date_dom 	= lab_date_range_block_dom.find(".js_end_date");
		let end_date 		= end_date_dom.val();

		let pl_results 		= $("#pl_results");
		let results_table 	= pl_results.find("#pl_results_table");
		let division_id 	= pl_results.find(".js_lab_division.active").data("divisionId");
		let url 			= pl_results.find(".js_lab_division.active").data("url");

		let date_bool = true;
		lab_date_range_block_dom.find("small").html('');

		if(start_date == "") {
			start_date_dom.closest("div").find("small").html("Тўлдириш мажбурий");
			date_bool = false;
		}
		if(end_date == "") {
			end_date_dom.closest("div").find("small").html("Тўлдириш мажбурий");
			date_bool = false;
		}

		if(date_bool) {
			$.post(url, {division_id:division_id, start_date:start_date, end_date:end_date}, function (html) {
				results_table.html(html);

				create_lab_result_datatable();

			}, "json");
		}
	});



	if($('.datatable_patients_lab').length > 0) {

		let url = "/hospitalzm/doctor/patients_lab/ajax_load_all";

		$('.datatable_patients_lab').DataTable({
			"processing": true,
			"serverSide": true,
			'serverMethod': 'post',
			"paging": true,
			"lengthMenu": [25],
			"ajax": {
				'url': url,
				// "success": function (res) {
				// 	console.log(res);
				// }
			},
			// columnDefs: [
			// 	{ "orderable": false, targets: -1 },
			// 	{ "width": "20%", "targets": 0 },
			// 	{ "width": "15%", "targets": 1 },
			// 	{ "width": "15%", "targets": 2 },
			// 	{ "width": "15%", "targets": 3 },
			// 	{ "width": "27%", "targets": 4 },
			// ],
			'columns': [
				{ data: 'last_name' },
				{ data: 'address' },
				{ data: 'username' },
				{ data: 'dob' },
				{ data: 'phone' },
				{ data: 'created_date' },
			],
		});
	}


});

function create_lab_result_datatable() {
	$(document).find('#lab_dashboard_dt').DataTable({
		"scrollX": true,
		// "scrollY": "500px",
		"scrollCollapse": true,
		"searching": false,
		"ordering": false,
		"paging" : false,
		"info": false,
		fixedColumns: {
			leftColumns: 3
		},
		columnDefs: [
			{ width: 200, height:200, targets: 0 },
			{ width: 50, targets: 0 },
			{ width: 50, targets: 0 },
		],
	});
}


