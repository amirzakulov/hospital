$(document).ready(function () {

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


	if($(".js_sticky_block").length > 0) {
		$(".js_sticky_block").sticky(
			{
				topSpacing:50,
				bottomSpacing: 10,
				zIndex: 10,
			}
		);
	}

	if($(".rich_text_content").length > 0) {
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
				['table', ['table']],
				['font', ['clear', 'size']],
				['view', ['codeview']],
			]
		});
		$("#template_ru").summernote({
			placeholder: 'Текст',
			tabsize: 2,
			height: 400,
			toolbar: [
				['font', ['bold', 'underline','italic']],
				['table', ['table']],
				['font', ['clear', 'size']],
				['view', ['codeview']],
			]
		});
	}


    $("input[name='last_name']").focusout(function () {
        var last_name = $(this).val();
        var suffix = last_name.substr(-2, 2);
        // var male_gender = ["va"];

        if(suffix == "va") {
            $('input[name="gender"][value="1"]').attr('checked',false);
            $('input[name="gender"][value="2"]').attr('checked',true);
        } else {
            $('input[name="gender"][value="1"]').attr('checked',true);
            $('input[name="gender"][value="2"]').attr('checked',false);
        }
    });


    /**
     * Umumiy uchirish. Delete tugmasini bosilganda, belgilangan item boshqa itemlarga bog'langan bulsa,
     * bu item boshqa itemlar bilan bog'langanligini eslatib, tasdiqlash modal oynasini ochadi, aks xolda
     * oddiy tasdiqlash oynasini ochadi
     * **/
    $(document).on("click",".js_delele_item", function () {
        var $this = $(this);
        var url = $this.data("href");
        var item_id = $this.data("id");
        var text = "";

        $.post(url, {id : item_id}, function (res) {
            if(res == true) {
                var $modal = $("#nodelete_notifier");
                text += "<p><i class='fa fa-exclamation-triangle text-danger'></i> Ушбу маълумот бошқа малумотлар билан боғланган.</p>";
                text += "<p>Шунинг учун бу маълумотни ўчираолмайсиз!</p>";
                $modal.find(".modal-body").html(text);
                $modal.modal("show");
            } else {
                var $modal = $("#confirm_delete_notifier");
                var yes_btn = $modal.find(".modal-footer .js_yes");
                yes_btn.data("url", url);
                yes_btn.data("id", item_id);
                text += "<p><i class='fa fa-remove fa-2x text-danger'></i> Ушбу маълумотни хақиқатан хам ўчирасизми?</p>";
                $modal.find(".modal-body").html(text);
                $modal.modal("show");
            }
        }, "json");
    });

    $(document).on("click", ".js_yes", function () {
        var $this = $(this);
        var url = $this.data("url");
        var item_id = $this.data("id");
        var $modal = $this.closest("#confirm_delete_notifier");
        var $modal_body = $modal.find(".modal-body");
        var row = $("#js_row_"+item_id);
        var text = "";
        $.post(url, {confirm:true, id:item_id}, function (res) {
            if(res.deleted == true) {
                text = "<i class='fa fa-info-circle fa-2x text-primary'></i> Маълумот муваффақиятли ўчирилди!";
                $modal_body.html(text);
                $modal.modal("hide");
                row.remove();
            }
            else
            {
                text = "<i class='fa fa-remove-circle fa-2x text-danger'></i> Маълумотни ўчириб бўлмади!";
                $modal_body.html(text);
                $modal.modal("hide");
            }
        }, "json");
    });


    /**
     * Region ID buyicha shaxarlarni olish
     * */
    $("#region_id").change(function () {
        var $this = $(this);
        var region_id = $this.val();
        var url = $this.data("url");
        var city_selectbox = $("#city_id");
        $.post(url, {"region_id":region_id}, function (res) {
            city_selectbox.html(res);
        }, "json");
    });

    /**
    * Laboratoriyada sub-lab qushish kerakmi yuqmi tanlaydi
    * */
    $(".js_sub_lab_add").change(function () {
        var $this = $(this);
        var sublab_block = $(".js_sublab_block");
        var norma_add_block = sublab_block.find(".js_norma_add");
        var sublab_add_block = sublab_block.find(".js_sublab_add");
        if($this.val() == 1 ) {
            norma_add_block.removeClass("d-none");
            sublab_add_block.addClass("d-none");
        } else {
            norma_add_block.addClass("d-none");
            sublab_add_block.removeClass("d-none");
        }
    });


    /************
    * Doktorlarni oyliklari
    * **************/
    $(".js_doctor_bill").click(function () {
        var $this = $(this);
        var doctor_bill_input = $this.closest("td").find(".js_doctor_bill_input");

        $this.parent().addClass("d-none");
        doctor_bill_input.removeClass("d-none");
    });

    $( "input" ).keypress(function(b) {
        var $this = $(this);
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if($this.hasClass("js_doctor_bill_amount") && keycode == 13) {
            b.preventDefault();
            var save_btn = $this.closest(".js_doctor_bill_input").find(".js_payment_save");
            $this.closest(".js_doctor_bill_input").find(".js_payment_save").trigger( "click" );
        }
    });

    $(".js_doctor_bill_amount").closest(".js_doctor_bill_input").find(".js_payment_save").trigger( "click" );

    $(".js_payment_save").click(function () {
        var $this = $(this);
        var tr = $this.closest("tr");
        var td = $this.closest("td");
        var doctor_bill_input_content = $this.closest(".js_doctor_bill_input");
        var doctor_bill_input = $this.closest(".input-group--doctor_bill");
        var tulangan_summa_content_dom = td.find(".js_tulangan_summa_content");
        var tulangan_summa_dom = td.find("input[name='tulangan_summa']");
        var tulangan_summa = tulangan_summa_dom.val();
        var tulov_summa_dom = doctor_bill_input.find("[name='amount']");
        var tulov_summa = tulov_summa_dom.val();
        var doctor_id = doctor_bill_input.find("input[name='doctor_id[]']").val();
        var url = $this.data("url");
        var klinika_qarzi_dom = tr.find(".js_klinika_qarzi");
        var sh_ulush = tr.find("input[name='dShareSum']").val();

        if(tulov_summa > 0) {
            $.post(url, {doctor_id:doctor_id, amount:tulov_summa}, function (res) {

                tulangan_summa_content_dom.removeClass("d-none");
                doctor_bill_input_content.addClass("d-none");
                tulangan_summa = parseInt(tulangan_summa) + parseInt(tulov_summa);
                tulov_summa_dom.val("");
                tulangan_summa_dom.val(tulangan_summa);
                tulangan_summa_content_dom.find(".js_tulangan_summa").html(tulangan_summa);
                var klinika_qarzi =  parseInt(sh_ulush) - parseInt(tulangan_summa);
                klinika_qarzi_dom.html(klinika_qarzi);
            }, "json");
        } else {
            $this.tooltip('show');
        }


    });

    $(".js_payment_cancel").click(function () {
        var $this = $(this);
        var td = $this.closest("td");
        var tulangan_summa_content_dom = td.find(".js_tulangan_summa_content");
        var doctor_bill_input_dom = td.find(".js_doctor_bill_input");

        doctor_bill_input_dom.addClass("d-none");
        tulangan_summa_content_dom.removeClass("d-none");
    });

    if($('.datetimepicker-salary').length > 0) {
        $('.datetimepicker-salary').datetimepicker({
            format: 'DD.MM.YYYY',
        });
    }

    $(".js_lab_partner_id").change(function () {
        var $this = $(this);
        var partner_price_dom = $(".js_lab_partner_price");
        if($.trim($this.val()).length !== 0) {
            partner_price_dom.closest(".row").removeClass("d-none");
        } else {
            partner_price_dom.closest(".row").addClass("d-none");
        }
    });

    /************
    * bermorlarni xonaga joylashtirish
    * */
    if($(".js_patient_searchbox").length > 0) {

        var patient_list_url = $(".js_patient_searchbox").data("url");//admin/registry/ajax_patiens_list
        var patient_details_url = $(".js_patient_searchbox").data("patientDetailsUrl");//admin/registry/ajax_patient_details

        $(".js_patient_searchbox").autocomplete({
            serviceUrl: patient_list_url,
            onSearchStart: function (query) {
				console.log(query)
                var $this = $(this);
                $this.closest(".js_patient_search_content").find(".js_patient_search_result_box").html('');
                $(".js_patient_search_result_box").hide();
            },
            onSelect: function (res) {
                var $this = $(this);
                var patient_id = res.patient_id;
                $.post(patient_details_url, {"patient_id":patient_id}, function (html) {
                    $this.closest(".js_patient_search_content").find(".js_patient_search_result_box").html(html);
                    $(".js_patient_search_result_box").slideDown("slow");

                }, "json");
            }
        });
    }

    if($("[name='room_autocomplete']").length > 0) {
        var room_url = $("[name='room_autocomplete']").data("url");
        $("[name='room_autocomplete']").autocomplete({
            serviceUrl: room_url,
            onSearchStart: function (query) {
                var url = $(this).data("url");
            },
            onSelect: function (res) {
                var f1 = $(this).closest("form");
                var patient_id_dom = f1.find("[name='patient_id']");
                patient_id_dom.val(res.data);
            }
        });
    }



    $(".js_assign_bed").click(function () {
        var $this = $(this);
        var bed_name = $this.data("bed");
        var bed_id = $this.data("bedId");
        var price = $this.data("price");

        var $modal = $("#assign_bed");
        var room_patient_searchbox = $modal.find(".js_room_patient_searchbox");
        var room_patient_add_form = $modal.find(".js_room_patient_add_form");

        room_patient_searchbox.find("[name='bed_id']").val(bed_id);
        room_patient_searchbox.find("[name='price']").val(price);
        room_patient_add_form.find("[name='bed_id']").val(bed_id);
        room_patient_add_form.find("[name='price']").val(price);

        $modal.find(".js_bed_name").html(bed_name);
        $modal.modal("show");
    });

    $(document).on("change", ".js_new_patient", function () {
        var $this = $(this);
        var room_patient_searchbox = $this.closest(".modal-body").find(".js_room_patient_searchbox");
        var room_patient_add_form = $this.closest(".modal-body").find(".js_room_patient_add_form");

        if(!$this.is(":checked")) {
            room_patient_searchbox.removeClass("d-none");
            room_patient_add_form.addClass("d-none");
        } else {
            room_patient_add_form.removeClass("d-none");
            room_patient_searchbox.addClass("d-none");
        }
    });

    $(document).on("click",".js_room_assign_old_patient", function(){
        var $this = $(this);
        var f1 = $this.closest("form");
        var url = $this.data("url");
        console.log(url);

        // var patient_id_dom  = f1.find("[name='patient_id']");
        // var patient_id      = patient_id_dom.val();
        // var start_date_dom  = f1.find("[name='start_date']");
        // var start_date      = start_date_dom.val();
        // var end_date_dom    = f1.find("[name='end_date']");
        // var end_date        = end_date_dom.val();
        // var bed_id_dom      = f1.find("[name='bed_id']");
        // var bed_id          = bed_id_dom.val();
        // var payment_type_dom = f1.find("[name='payment_type']");
        // var payment_type    = payment_type_dom.val();
        // var price_dom       = f1.find("[name='price']");
        // var price           = price_dom.val();
        // var data            = {patient_id:patient_id, bed_id:bed_id, payment_type:payment_type, price:price, start_date:start_date, end_date:end_date};

        var f1_data = f1.serializeArray();

        $.post(url, f1_data, function (res) {
            console.log(res);
            f1.find(".form-control").removeAttr("style");
            f1.find(".form-group").find(".invalid-feedback").html("").css("display","none");
            if(res["errors"] != false) {
                $.each(res["errors"], function (index, value) {
                    f1.find(".form-group").find("input[name='"+index+"']").css({"border":"1px solid red"});
                    f1.find(".form-group").find("input[name='"+index+"']").closest(".form-group").find(".invalid-feedback").html(value).css("display","block");
                });
            } else {
                var row = $("#js_row_"+res["data"]["bed_id"]);
                row.find(".js_start_date").html(res["data"]["start_date"]);
                row.find(".js_end_date").html(res["data"]["end_date"]);
                row.find(".js_patient_name").html(res["data"]["patient_name"]);
                row.find(".js_room_paid").html(res["data"]["paid"]);
                row.find(".js_room_total").html(res["data"]["total"]);
                row.find(".js_busy").find("span").removeClass("fa-circle-o").addClass("fa-circle text-danger");
                row.find(".js_assign_bed").addClass("d-none");
                row.find(".js_view_bed").removeClass("d-none");
                var $modal = $("#assign_bed");
                $modal.modal("hide");
            }
        }, "json");
    });

    $(document).on("click",".js_room_assign_new_patient", function(){
        var $this = $(this);
        var f2 = $this.closest("form");
        var url = $this.data("url");

        var f2_data = f2.serializeArray();

        $.post(url, f2_data, function (res) {

            f2.find(".form-control").removeAttr("style");
            f2.find(".form-group").find(".invalid-feedback").html("").css("display","none");
            if(res["errors"] != false) {
                $.each(res["errors"], function (index, value) {
                    f2.find(".form-group").find("#"+index).css({"border":"1px solid red"});
                    f2.find(".form-group").find("#"+index).closest(".form-group").find(".invalid-feedback").html(value).css("display","block");
                });
            } else {
                var row = $("#js_row_"+res["data"]["bed_id"]);
                row.find(".js_start_date").html(res["data"]["start_date"]);
                row.find(".js_end_date").html(res["data"]["end_date"]);
                row.find(".js_patient_name").html(res["data"]["patient_name"]);
                row.find(".js_room_total").html(res["data"]["total"]);
                row.find(".js_room_paid").html(res["data"]["paid"]);
                row.find(".js_busy").find("span").removeClass("fa-circle-o").addClass("fa-circle text-danger");
                row.find(".js_assign_bed").addClass("d-none");
                row.find(".js_view_bed").removeClass("d-none");
                var $modal = $("#assign_bed");
                $modal.modal("hide");
            }
        }, "json");
    });

    $(".js_view_bed").click(function () {
        var $this       = $(this);
        var bed_id      = $this.data("bedId");
        var bed_name    = $this.data("bed");
        var bed_price   = $this.data("price");
        var url         = $this.data("url");//admin/rooms/ajax_show_patient_room
        var $modal      = $("#view_room");
        var f3          = $modal.find("form");
        var save_button     = f3.find(".js_patient_room_update");
        var patient_room_dom= $modal.find("[name='patient_room_id']");
        var patient_id_dom  = $modal.find("[name='patient_id']");
        var patient_name_dom= $modal.find("[name='room_autocomplete']");
        var start_date_dom  = $modal.find("[name='start_date']");
        var end_date_dom    = $modal.find("[name='end_date']");
        var payment_id_dom  = $modal.find("[name='payment_id']");
        var price_dom       = $modal.find("[name='price']");
        var bed_id_dom      = $modal.find("[name='bed_id']");
        var total_dom       = $modal.find("[name='total']");
        var paid_dom        = $modal.find("[name='paid']");
        var debt_off_dom    = $modal.find("[name='debt_off']");
        save_button.removeClass("d-none");
        $.post(url, {bed_id:bed_id}, function (res) {

            patient_room_dom.val(res["id"]);
            patient_id_dom.val(res["patient_id"]);
            patient_name_dom.val(res["last_name"]+" "+res["first_name"]);
            start_date_dom.val(res["start_date"]);
            end_date_dom.val(res["end_date"]);
            payment_id_dom.val(res["payment_id"]);
            price_dom.val(bed_price);
            bed_id_dom.val(bed_id);
            total_dom.val(res["total"]);
            paid_dom.val(res["paid"]);

            // console.log(res);

            if(res["paid"] < res["total"]) {

                debt_off_dom.closest(".row").removeClass("d-none");
                paid_dom.prop("readonly",true);
            }else {
                paid_dom.prop("readonly",false);
            }

            if(res["end_date_time"] < res["today"]) {
                save_button.addClass("d-none");
            }

            if(res["start_date_time"] < res["today"]) {
                patient_name_dom.prop("readonly","readonly");
                start_date_dom.prop("readonly","readonly");
            } else {
                patient_name_dom.removeAttr("readonly");
                start_date_dom.removeAttr("readonly");
            }

            $modal.find(".js_bed_name").html(bed_name);
            $modal.modal("show");
        }, "json");
    });

    if($('.datetimepicker_minDate').length > 0) {
        var d = new Date();
        var yesterday = d.setDate(d.getDate());

        $('.datetimepicker_minDate').datetimepicker({
            // minDate: yesterday,
            format: 'DD.MM.YYYY',
            widgetPositioning: {
                // vertical: 'top'
            },
        })
    }

    $(document).on("click", ".js_patient_room_update", function () {
        var $this = $(this);
        var f1 = $this.closest("form");
        var url = $this.data("url");//admin/rooms/ajax_update_patient_room
        var $modal = $("#view_room");
        var f1_data = f1.serializeArray();

        $.post(url, f1_data, function (res) {

            f1.find(".form-control").removeAttr("style");
            f1.find(".form-group").find(".invalid-feedback").html("").css("display","none");
            if(res["errors"] != false) {
                $.each(res["errors"], function (index, value) {
                    f1.find(".form-group").find("#"+index).css({"border":"1px solid red"});
                    f1.find(".form-group").find("#"+index).closest(".form-group").find(".invalid-feedback").html(value).css("display","block");
                });
            } else {
                var row = $("#js_row_"+res["data"]["bed_id"]);
                row.find(".js_start_date").html(res["data"]["start_date"]);
                row.find(".js_end_date").html(res["data"]["end_date"]);
                row.find(".js_patient_name").html(res["data"]["patient_name"]);
                row.find(".js_room_paid").html(res["data"]["paid"]);
                row.find(".js_room_total").html(res["data"]["total"]);
                row.find(".js_busy").find("span").removeClass("fa-circle-o").addClass("fa-circle text-danger");
                row.find(".js_assign_bed").addClass("d-none");
                row.find(".js_view_bed").removeClass("d-none");

                $modal.modal("hide");
                var debt_off_dom    = $modal.find("[name='debt_off']").val("");

            }
        }, "json");

    });

    $(document).on("blur",".js_room_patient_searchbox [name='start_date'], .js_room_patient_searchbox [name='end_date'], " +
        ".js_room_patient_add_form [name='start_date'], .js_room_patient_add_form [name='end_date']", function () {
        var $this           = $(this);
        var f               = $this.closest("form");
        var start_date_dom  = f.find("[name='start_date']");
        var start_date      = start_date_dom.val();
        var end_date_dom    = f.find("[name='end_date']");
        var end_date        = end_date_dom.val();
        var price           = f.find("[name='price']").val();

        if(start_date.length > 0 && end_date.length > 0) {
            // console.log(start_date +" "+ end_date);
            var days = get_between_days(start_date, end_date);
            var total_dom = f.find("[name='total']");
            var paid_dom = f.find("[name='paid']");
            var total_sum = price*days;
            total_dom.val(total_sum);
            paid_dom.val(total_sum);
        }
    });

    /***************
    * pos Printerni sozlamalari
	 * uchirib-yoqish, logo chop etish, QR Code chop etish
    * */
	$(".js_switch_pos_printer_item").change(function () {
		let $this   			= $(this);
		let status  			= ($this.prop("checked") == true) ? 1:0;
		let settings_item_id  	= $this.data("settingsItemId");
		let url     			= $this.data("url");//admin/settings/posprint/ajax_update_setting_value

		$.post(url, {status:status, settings_item_id:settings_item_id}, function (res) {}, "json")
	});

	$(".js_select_pos_printer").change(function () {
		let $this   					= $(this);
		let selected_pos_printer_id  	= $this.val();
		let url     					= $this.data("url");//admin/settings/posprint/ajax_select_printer

		$.post(url, {selected_pos_printer_id:selected_pos_printer_id}, function (res) {
			if(res === true) {
				alert("Маълумотлар мувоффақиятли сақланди!")
			}
		}, "json")
	});

	/***************
	 * Laboratoriya printer sozlamalari
	 * */
	$(".js_save_lab_print_details").click(function(e){
		e.preventDefault();
		let $this = $(this);
		let $f = $this.closest("form");
		let send_data = $f.serializeArray();
		let url = $f.attr("action");//admin/settings/lab_print/ajax_save_data
		$.post(url, {data: send_data}, function (res) {
			if(res === true) {
				setTimeout(function () {
					alert("Маълумотлар мувоффақиятли сақланди!")
				}, 500)
			}
		}, "json");
	});
    /************
    * -----------------
    * */

    /*****
     * contentdan qidirish
     * */
    if($(".search-items").length > 0) {
        $('#doc_search, #lab_search, #uzi_search, #bed_search').hideseek({
			headers: '.origin',
        });


        $(".search-items").keyup(function () {
            var $this = $(this);
            var remove_btn = $this.closest("div").find(".js_clear_text");
            remove_btn.parent().removeClass("d-none");
        });

        $(".js_clear_text").click(function () {
            var $this = $(this);
            var searchbox = $this.closest(".input-group").find(".search-items");
            searchbox.val("");
        });
    }


    /**************
     *********************************************
    * */
    $(".js_show_cash_expenses").click(function () {
        var $this = $(this);

        get_cash_today();
        create_datatable_cash();

        var $modal = $("#expenses");
        $modal.modal("show");
    });

    $('#expenses').on('hidden.bs.modal', function (e) {
        var $this = $(this);
        $this.find("form .form-control").removeAttr("style");
    })


    $(document).on("click", ".js_add_expense", function () {
        var $this = $(this);
        var form = $this.closest("form");
        var url = form.attr("action");//admin/registry/ajax_add_expenses
        var amount_dom = form.find("input[name='amount']");
        var amount = amount_dom.val();
        var reason_dom = form.find("textarea[name='reason']");
        var reason = reason_dom.val();
        var payment_type_id = form.find("select[name='payment_type_id']").val();
        var expense_type_id = form.find("select[name='expense_type_id']").val();

        $.post(url, {amount:amount, reason:reason, payment_type_id:payment_type_id, expense_type_id:expense_type_id}, function (res) {

            form.find(".form-control").removeAttr("style");
            if(res["errors"] != false) {
                $.each(res["errors"], function (index, value) {
                    form.find("#"+index).css({"border":"1px solid red"});
                });
            }
            else
            {
                amount_dom.val("");
                reason_dom.val("");

                // var cash_url = $(document).find(".js_show_cash_expenses").data("urlCash");
                // var datatable_url = $this.data("url");
                get_cash_today();
                create_datatable_cash();
            }
        }, "json");
    });

    $(document).on("click",".js_expense_edit_field", function () {
        var $this = $(this);
        var tr = $this.closest("tr");
        var expense_update_box = tr.find(".js_expense_update_box");
        var remove_btn = tr.find(".js_expense_remove");
        remove_btn.hide();
        var expense_cell = tr.find(".js_expense_cell");
        var expense_edit = tr.find(".js_expense_edit");
        if($this.hasClass("js_expense_edit")) { //edit tugmasini bosganda
            $this.addClass("d-none");
            expense_update_box.removeClass("d-none");
            expense_cell.find(".js_expense_cell_text").addClass("d-none");
            expense_cell.find(".js_expense_cell_input").removeClass("d-none");
        } else if($this.hasClass("js_expense_cancel")) {//cancel tugmasini bosganda
            expense_update_box.addClass("d-none");
            expense_edit.removeClass("d-none");
            expense_cell.find(".js_expense_cell_text").removeClass("d-none");
            expense_cell.find(".js_expense_cell_input").addClass("d-none");
            remove_btn.show();
        } else if($this.hasClass("js_expense_apply")) {//saqlash tugmasini bosganda
            remove_btn.show();
            var url = $this.data("url");
            var expense_id = $this.data("id");
            var amount_dom = tr.find("[name='amount[]']");
            var amount = amount_dom.val();
            var amount_text_dom = amount_dom.closest(".js_expense_cell").find(".js_expense_cell_text");

            var description_dom = tr.find("[name='reason[]']");
            var description = description_dom.val();
            var description_text_dom = description_dom.closest(".js_expense_cell").find(".js_expense_cell_text");

            var data = {
                expense_id:expense_id,
                amount:amount,
                reason:description
            };

            $.post(url, data, function (res) {
                tr.find(".form-control").removeAttr("style");
                if(res["errors"] != false) {

                    $.each(res["errors"], function (index, value) {
                        expense_cell.find("#"+index+"_"+expense_id).css({"border":"1px solid red"});
                    });
                }
                else
                {
                    amount_text_dom.text(amount);
                    description_text_dom.text(description);
                    expense_update_box.addClass("d-none");
                    expense_edit.removeClass("d-none");
                    expense_cell.find(".js_expense_cell_text").removeClass("d-none");
                    expense_cell.find(".js_expense_cell_input").addClass("d-none");

                    get_cash_today();
                }
            }, "json");

        }
    });

    $(document).on("click", ".js_expense_remove", function (e) {
        e.preventDefault();
        var $this = $(this);
        var expense_id = $this.data("id");
        var url = $this.data("url");
        var row = $this.closest("tr");

        $.post(url, {expense_id:expense_id}, function (res) {
            if(res == true) {
                row.remove();
                get_cash_today();
                create_datatable_cash();
            }
        }, "json");

    });

    $(".js_do_payment").click(function (e) {
        e.preventDefault();
        var $this = $(this);
        var form = $this.closest("form");
        var payment_id = form.find("input[name='payment_id']").val();
        var url = $this.data("url");//admin/registry/ajax_do_payment

        $.post(url, {"payment_id":payment_id}, function (res) {
            if(res == false) {

            } else {
                var backUrl = res;
                window.location.replace(backUrl);
            }
        }, "json");
    });

    if($(".report_datatable").length) {
		$('.report_datatable').DataTable({
			"scrollY": "25rem",
			"scrollCollapse": true,
			"searching": false,
			"ordering": false,
			"paging" : false,
		});
	}


	$(".js_report_ribbon_btn").click(function(){
		var $this = $(this);
		var span = $this.find("span");
		if(span.hasClass("fa-chevron-down")) {
			span.removeClass("fa-chevron-down").addClass("fa-chevron-up");
		} else {
			span.removeClass("fa-chevron-up").addClass("fa-chevron-down");
		}
	});

    /**************
     * *******************************************
    * */

	$(".js_send_data_to_print").click(function () {
		let $this = $(this);
		let url = $this.data("url");
		$.post(url, {}, function () {

		}, "json");
	});

	$(".js_bill_edit").click(function (){
		let $this = $(this);
		let bill_edit_block = $this.closest("div")
		let bill_input_block = $this.closest(".js_bill_amount").find(".js_bill_input")

		bill_input_block.removeClass("d-none");
	});

	$(".js_bill_input button").click(function (e){
		e.preventDefault()
		let $this = $(this);
		let table = $this.closest("table")
		let row = $this.closest("tr")
		let bill_edit_block = $this.closest(".js_bill_amount").find(".js_bill_edit").closest("div")
		let bill_input_block = $this.closest(".js_bill_input")
		let url   = $this.data("url");
		let partner_id 	= $this.data("partnerId");
		let amount 		= bill_input_block.find("input").val();
		let current_month_dom = row.find(".js_current_month_salary")
		let current_month_text_dom = current_month_dom.find("span")
		let current_month_inout_dom = current_month_dom.find("input")
		let current_month_amount = current_month_inout_dom.val();
		let bill_amount_total_text_dom = table.find(".js_bill_amount_total span")
		let bill_amount_total_input_dom = table.find(".js_bill_amount_total input")
		let bill_amount_total = parseInt(bill_amount_total_input_dom.val())

		if($this.hasClass("btn-success")) {
			if(amount > 0) {
				$.post(url, {partner_id: partner_id, amount: amount}, function (res){ //admin/partners/ajax_partner_checkout
					let bill_amount = res.amount - res.bill_amount
					bill_edit_block.find("span:first").html(number_format(bill_amount, 2, ',', ' '));

					current_month_amount = parseInt(current_month_amount) + parseInt(amount)
					current_month_text_dom.html(number_format(current_month_amount, 2, ',', ' '))
					current_month_inout_dom.val(current_month_amount)

					bill_amount_total = bill_amount_total - amount

					bill_amount_total_text_dom.html(number_format(bill_amount_total, 2, ',', ' '))
					bill_amount_total_input_dom.val(bill_amount_total)


				}, "json");
				bill_edit_block.removeClass("d-none").addClass("d-inline-block");
				bill_input_block.addClass("d-none");
			} else {
				alert("Қиймат бўш бўлиши мумкин эмас!")
			}
		} else {
			bill_input_block.addClass("d-none")
		}
	});

	// $(".js_show_partner_bill_form").click(function (){
	// 	let $this = $(this);
	// 	let $modal = $("#partner_bill_form");
	// 	let partner_id = $this.data("partnerId");
	// 	let partner_name = $this.data("partnerName");
	// 	let partner_company = $this.data("partnerCompany");
	//
	// 	$modal.find(".js_partner_name").html(partner_name);
	// 	$modal.find(".js_partner_company").html(partner_company);
	//
	// 	$modal.find("[name='partner_id']").val(partner_id);
	// 	$modal.modal("show");
	// });

	$(".js_partner_bill_form_save").click(function(e){
		e.preventDefault()
		let $this = $(this);
		let $modal = $this.closest(".modal")
		let $form = $this.closest("form");
		let url   = $form.attr("action");
		let partner_id 	= $form.find("[name='partner_id']").val();
		let amount 		= $form.find("[name='amount']").val();

		$.post(url, {partner_id: partner_id, amount: amount}, function (res){

			window.location.reload();

			// let bill_amount = res.amount - res.bill_amount
			// $(document).find(".js_row_"+partner_id).find(".js_bill_amount").html(bill_amount)
			// $form.find("[name='partner_id']").val("");
			// $form.find("[name='amount']").val("");
			// $modal.modal("hide");
		}, "json");

	});

	$(".js_muliply_service_module").click(function(){
		const wrapper = $("#partner_service_module_box");
		const row = wrapper.find(".row:first-child");
		const new_row = row.clone()
		new_row.find(".js_muliply_service_module").addClass("d-none")
		new_row.find(".js_remove_service_module").removeClass("d-none");
		new_row.appendTo(wrapper)

	});

	$(document).on("click",".js_remove_service_module", function(){
		let $this = $(this);
		const row = $this.closest(".row").remove();

	});

	$(".js_report_tab").click(function(){
		const $this 		= $(this);
		const url 			= $this.data('href');
		const ul  			= $this.closest('ul');
		const start_date 	= ul.data('startDate');
		const end_date 		= ul.data('endDate');
		const js_container 	= $("#js_inout_block");

		ul.find("a.nav-link").removeClass('active');
		$this.addClass('active')

		let data = {
			start_date: start_date,
			end_date: end_date
		}

		$.post(url, data, function(res){
			js_container.html(res);
		}, "json")
	});

	$(document).on("click",".js_doctor_patients", function () {
		const $this 		= $(this)
		const url   		= $this.data('href');
		const doctor_id 	= $this.data('doctorId');
		const start_date 	= $this.data('startDate');
		const end_date 		= $this.data('endDate');
		const js_container 	= $("#js_inout_block");

		let data = {
			doctor_id: doctor_id,
			start_date: start_date,
			end_date: end_date
		}

		$.post(url, data, function(res){
			js_container.html(res);
		}, "json")

	})
});


function get_between_days(start_date_str, end_date_str) {
    // JavaScript program to illustrate
    // calculation of no. of days between two date

    //date formatini uzgartirib olamiz
    var sdate_string = moment(start_date_str, "DD.MM.YYYY").format("MM/DD/YYYY");
    var edate_string = moment(end_date_str, "DD.MM.YYYY").format("MM/DD/YYYY");
    // To set two dates to two variables
    var date1 = new Date(sdate_string);
    var date2 = new Date(edate_string);

// To calculate the time difference of two dates
    var Difference_In_Time = date2.getTime() - date1.getTime();

// To calculate the no. of days between two dates
    var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

//To display the final no. of days (result)
    return Difference_In_Days;
}

/***********
 * Chiqimlar uchun datatable yaratish
 * */
function create_datatable_cash() {
    var url = $(document).find(".js_show_cash_expenses").data("url");//admin/registry/ajax_show_expenses
    table = $('#js_datatable_cash').DataTable();
    table.destroy();

    return table = $('#js_datatable_cash').DataTable({
        "scrollY": "300px",
        "scrollCollapse": true,
        "paging": false,
        "bFilter": false,
        "searching": true,
        "ordering": true,
        "order":[[ 0, "desc" ]],
        "info":     false,
        "autoWidth": true,
        // "lengthMenu": [[10], [10]]
        "language": {
            "emptyTable": "Маълумотлар топилмади",
            "sInfoEmpty":"Umumiy 0 yozuvlardan 0 dan 0 gachasi ko'rsatilmoqda",
            "oPaginate": {
                "sFirst":       "Биринчи",
                "sPrevious":    "Аввалги",
                "sNext":        "Кейинги",
                "sLast":        "Сўнгги"
            },
            "sSearch":          "Қидириш:",
        },
        columnDefs: [
            { "orderable": false, targets: -1 },
            { "width": "20%", "targets": 0 },
            { "width": "15%", "targets": 1 },
            { "width": "15%", "targets": 2 },
            { "width": "15%", "targets": 3 },
            { "width": "27%", "targets": 4 },
            { "width": "8%", "targets": 5 },
        ],
        "ajax": {
            "url": url,
            "type": "POST",
			// "success": function (res) {
			// 	console.log(res);
			// }
        },
    });
}

/***********
 * Kassani kurish
 * */
function get_cash_today() {
    var url = $(document).find(".js_show_cash_expenses").data("urlCash");//admin/registry/ajax_get_cash_today
    $.post(url, {}, function (res) {
        var wrapper = $("#expenses");
        var total_income_dom    = wrapper.find(".js_total_income");
        var total_expense_dom   = wrapper.find(".js_total_expense");
        var total_cash_dom      = wrapper.find(".js_total_cash");

        // console.log(res);

        total_income_dom.text(number_format(res["real_payment"], 2, ',', ' '));
        total_expense_dom.text(number_format(res["total_expenses"], 2, ',', ' '));
        total_cash_dom.text(number_format((res["real_payment"] - res["total_expenses"]), 2, ',', ' '));
    }, "json");
}

/**********
 *  number_formatting
 * ********/
function number_format (number, decimals, dec_point, thousands_sep) {
    // number = number.toFixed(decimals);

    var nstr = number.toString();
    nstr += '';
    var x = nstr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? dec_point + x[1] : '';
    var rgx = /(\d+)(\d{3})/;

    while (rgx.test(x1))
        x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');

    return x1 + x2;
}
