$(document).ready(function () {

    //Malum hududni print qilish
    $(document).on('click','.printBtn', function(){

        $(document).find('.printArea').printElement({
            title:'Bek Ultra Med',
            css: 'extend',
            ecss:'.zmlogo{display: block; margin-bottom:20px;}',
            keepHide: [".printBtn", ".hideElement"],

        });
    });



    /** **********************************************************
 * Bemorlar uchun
 * *********************************************************** */

    /**********************************************
     * ***********
     * Funksiyalar boshlandi
     * ***********
     * ********************************************/

    /**
     * 1. Tanlangan itemlarni blocki **/
    function selected_item(item_id, item_label, status, item_count, item_price) {
        var block = '<li class="list-group-item js_selected_item js_item_'+item_id+'" data-id="'+item_id+'">\n' +
                        '<div class="pl-2 js_selected_item_label"><div class="text-info">'+item_label+'</div>' +
						'<div class="text-danger js_selected_item_price">'+item_count+' X '+item_price+' = '+(item_price * item_count)+'</div></div>'

                        if(status == 0) {
                            block +='<button type="button" class="close js_close_selected_item position-absolute text-danger" style="right: 0; top: 0; padding: 5px;" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>\n';
                        }
                    block += '</li>';

        return block;
    }

    /**
     * 2. Fondagi rasmni hide-show qilish **/
    function bg_image_hide_show(total, modal_content) {
        var selected_items_menu = modal_content.find(".js_selected_items_menu");
        if(total > 0) {
            selected_items_menu.removeClass("selected_items_menu");
        } else {
            selected_items_menu.addClass("selected_items_menu");
        }
    }

    /**
     * 3. Tanlangan barcha itemlarni summasini qushish **/
    function summarize_all_selected(modal_content) {

        if(modal_content.find(".js_selected_items_date_range").length > 0) {
            var room_start_date = modal_content.find(".js_room_date_start").val();
            var room_end_date = modal_content.find(".js_room_date_end").val();

            var start = moment(room_start_date, "DD.MM.YYYY");
            var end = moment(room_end_date, "DD.MM.YYYY");
            var room_days = moment.duration(end.diff(start)).asDays();
        }

        var selected_items = modal_content.find(".js_form").find("input.js_select_item:checked");
        var total_sum = 0;
        $.each(selected_items, function (index, checkbox) {
        	var item_price = $(checkbox).data("price");
        	var item_count = $(checkbox).siblings(".js_item_count_value_input").val();
			if(modal_content.find(".js_selected_items_date_range").length > 0) {
				item_count = room_days;
			}
            total_sum += item_price * item_count;
        });

        // if(modal_content.find(".js_selected_items_date_range").length > 0) {
        //     total_sum = total_sum * room_days;
        // }
        return total_sum;
    }

    /**
     * 4. Faqat tanlangan blockdagi barcha itemlarni summasini qushish (masalan: doctors yoki labs yoki uzis)
     * param @js_class = "js_form_doctors yoki js_form_labs yoki js_form_uzis yoki js_form_rooms"
     * **/
    function summarize_one_block(js_class, modal_content) {

        if(js_class == "js_form_rooms") {
            var room_start_date = modal_content.find(".js_room_date_start").val();
            var room_end_date = modal_content.find(".js_room_date_end").val();

            var start = moment(room_start_date, "DD.MM.YYYY");
            var end = moment(room_end_date, "DD.MM.YYYY");
            var room_days = moment.duration(end.diff(start)).asDays();
        }

        var selected_items = modal_content.find("."+js_class).find("input.js_select_item:checked");
        var total_sum = 0;
        $.each(selected_items, function (index, checkbox) {
        	var item_price = $(checkbox).data("price");
			var item_count = $(checkbox).siblings(".js_item_count_value_input").val();
			if(js_class == "js_form_rooms") {
				item_count = room_days;
			}
            total_sum += (item_price * item_count);
        });

        // if(js_class == "js_form_rooms") {
        //     total_sum = total_sum * room_days;
        // }

        return total_sum;
    }

    /**
     * 5. js_selected_items_menu
     * **/
    function selected_items_menu_hide_show(this_item) {

		let $this               = this_item;
		let modal_content       = $this.closest(".js_items_content");
		let $js_form            = $this.closest(".js_form");
		let is_radio            = this_item.is(':radio');
		let item_id             = $this.val();
        let item_label          = $this.data("title");
        let item_price          = $this.data("price");
		var item_count          = $this.siblings(".js_item_count_value_input").val();
		let checkbox_checked    = $this.is(':checked');

		if(is_radio == true) {
			let date_content 	= modal_content.find(".js_selected_items_date_range");
			let start_date_dom 	= date_content.find(".js_room_date_start");
			let end_date_dom 	= date_content.find(".js_room_date_end");
			item_count = get_count_of_days(start_date_dom.val(), end_date_dom.val())
		}

        let block               = selected_item(item_id, item_label, 0, item_count, item_price);
		let checkbox_block_dom 	= $this.closest(".checkbox");
        let selected_items_menu = modal_content.find(".js_selected_items_menu");

        //Tanlangan item qaysi bulimga tegishliligini aniqlab olamiz (doctorsgami, labsgami yoki uzigami)
        let selected_items_block = selected_items_menu.find(".js_selected_doctors");
        let js_form_class = "js_form_doctors";
        if($js_form.hasClass("js_form_labs")) {
            selected_items_block = selected_items_menu.find(".js_selected_labs");
            js_form_class = "js_form_labs";
        }

        if($js_form.hasClass("js_form_uzis")) {
            selected_items_block = selected_items_menu.find(".js_selected_uzis");
            js_form_class = "js_form_uzis";
        }

		if($js_form.hasClass("js_form_services")) {
			selected_items_block = selected_items_menu.find(".js_selected_services");
			js_form_class = "js_form_services";
		}

		if($js_form.hasClass("js_form_rooms")) {
			selected_items_block = selected_items_menu.find(".js_selected_rooms");
			js_form_class = "js_form_rooms";
		}

        var selected_block_tulovga = selected_items_block.find(".js_selected_items--tulovga");
        var selected_items_title = selected_block_tulovga.find(".js_selected_items_title");
        var one_block_total_sum_dom = selected_block_tulovga.find(".js_total_sum--tulovga");
        var one_block_total_sum = summarize_one_block(js_form_class, modal_content);

		if(is_radio) {
			let allRadioBoxes = $js_form.find(".checkbox.bg-info");
			allRadioBoxes.removeClass("bg-info text-white").addClass("bg-light");
		}

        if(checkbox_checked == true) {
            if(is_radio) {
                // $this.closest(".js_form_rooms").find(".checkbox").css("background-color", "");
                selected_block_tulovga.find(".js_selected_item").remove();
            }
			checkbox_block_dom.removeClass("bg-light text-dark").addClass("bg-info text-white");
            if(selected_block_tulovga.find(".js_selected_item").length > 0) {
				if(selected_block_tulovga.find(".js_selected_item.js_item_"+item_id).length > 0) {
					selected_block_tulovga.find(".js_selected_item.js_item_"+item_id).replaceWith(block);
				} else {
					selected_block_tulovga.find(".js_selected_item").last().after(block);
				}
            } else {
                selected_items_title.after(block);
                selected_block_tulovga.removeClass("d-none");
            }
            one_block_total_sum_dom.text("Жами: "+one_block_total_sum);
            one_block_total_sum_dom.removeClass("d-none");

            selected_items_block.removeClass("d-none");
        } else {

			checkbox_block_dom.removeClass("bg-info text-white").addClass("bg-light");
			checkbox_block_dom.find(".js_item_count_value_input").val(1)
			checkbox_block_dom.find(".js_item_count_value_text").html("1")

            one_block_total_sum_dom.text("Жами: "+one_block_total_sum);
            selected_block_tulovga.find(".js_item_"+item_id).remove();

            if(summarize_one_block(js_form_class, modal_content) == 0) {
                selected_block_tulovga.addClass("d-none");
            }

            if(one_block_total_sum < 1) {
                selected_items_block.addClass("d-none");
            }
        }
    }

    /** **********************
     * 6. yigindi_block
     ** **********************/
    function yigindi_block_hide_show(total, type, payment_details) {

        var js_item_content = $(".js_items_content");
        bg_image_hide_show(total, js_item_content);
		var yigindi_block   = $(".yigindi_block");
		// var days = 1;
		if($(".js_selected_items_date_range").length > 0) {
			var date_content 	= yigindi_block.find(".js_selected_items_date_range");
			var start_date_dom 	= date_content.find(".js_room_date_start");
			var end_date_dom 	= date_content.find(".js_room_date_end");


			if(total == 0) {
				var date_ = new Date();
				var d1 = String(date_.getDate()).padStart(2, '0');
				var d2 = String(date_.getDate()+1).padStart(2, '0');
				var mm = String(date_.getMonth() + 1).padStart(2, '0'); //January is 0!
				var yyyy = date_.getFullYear();

				var today 	 = d1 + '.' + mm + '.' + yyyy;
				var tomorrow = d2 + '.' + mm + '.' + yyyy;

				start_date_dom.val(today);
				end_date_dom.val(tomorrow);
			}
		}

        let total_dom       = $(".js_selected_items_sum_total").find("#total");
		let paid_dom        = $(".js_selected_items_sum_tulandi").find("#paid");
		let debt_dom        = $(".js_selected_items_sum_qarzingiz").find("#debt");
		let discount_type_dom    = $(".js_selected_items_discount").find("#discount_type");
		let discount_value_dom    = $(".js_discount").find("#discount_value");

		let by_cash_dom        = $(".js_selected_items_payment_type").find("#by_cash");
		let by_card_dom        = $(".js_selected_items_payment_type").find("#by_card");
		let by_bank_dom        = $(".js_selected_items_payment_type").find("#by_bank");

        var paid = paid_dom.val();
        var debt = debt_dom.val();
		var discount_type 	= discount_type_dom.val();
		var discount_value 	= discount_value_dom.val();

		var by_cash = by_cash_dom.val();

        if (typeof payment_details === 'undefined') {

            var discount = discount_value;
            if(discount_type == 2) {
                discount = (discount_value / 100) * total;
            }

            if(total > 0) {
                if(type == "items") {
                    var result = item_change_sum(total, paid, debt, discount);
                    paid = result.paid;
                    debt = result.debt;
                }

                yigindi_block.removeClass("d-none");
                total_dom.removeClass("d-none");
            } else {
                yigindi_block.addClass("d-none");
                total_dom.addClass("d-none");

                clear_payment_inputs(); //qiymatlarni 0 ga tenglab quyamiz
            }

			by_cash_dom.val(paid);
			by_card_dom.val(0);
			by_bank_dom.val(0);
        } else {
            total 	= payment_details["total"];
            paid 	= payment_details["paid"];
            debt 	= payment_details["debt"];
			discount_type =  payment_details["discount_type"];

			if(discount_type > 0) {
				discount_type_dom.val(discount_type);
				discount_type_dom.select2(
					{
						"results": [
							{
								"id": '',
								"selected": true,
							},
						],
						width: '100%',
						"pagination": {
							"more": true
						}
					}
				);
			} else {
				discount_type_dom.val('').trigger('change');
			}

			discount = payment_details["discount_value"];
			discount_value_dom.val(discount);
			if(discount_type > 0) {
				discount_value_dom.closest(".js_discount").removeClass("d-none");
			} else {
				discount_value_dom.closest(".js_discount").addClass("d-none");
			}

            yigindi_block.removeClass("d-none");
            total_dom.removeClass("d-none");

			by_cash_dom.val(payment_details["by_cash"]);
			by_card_dom.val(payment_details["by_card"]);
			by_bank_dom.val(payment_details["by_bank"]);
        }

        total_dom.val(total);
        paid_dom.val(paid);
        debt_dom.val(debt);

    }

    /**
     * 7. tulov inputlarini tozalash
     * **/
    function clear_payment_inputs() {
        $(".js_selected_items_sum_total").find("#total").val(0);
        $(".js_selected_items_sum_qarzingiz").find("#debt").val(0);
        $(".js_selected_items_sum_tulandi").find("#paid").val(0);
        $(".js_selected_items_payment_type").find("#by_cash").val(0);
        $(".js_selected_items_payment_type").find("#by_card").val(0);
        $(".js_selected_items_payment_type").find("#by_bank").val(0);
        $(".js_discount").find("#discount_value").val(0);
    }

    /**
     * 8. Chegirmalar fieldlarini yashirib-ochish
     * **/
    function hide_discount_fields() {

    }

    /**
     * 9. kunni formatini uzgartirish
     * **/
    function change_date_format(ddate, format) {
        var dateAr = ddate.split('-');

        var newDateFormat = "";
        if(format == "DD.MM.YYYY") {
            newDateFormat = dateAr[2].slice(0, 2) + '.' + dateAr[1] + '.' + dateAr[0];
        }

        return newDateFormat;
    }

    /**
     * 10. Itemlar tanlanganda yoki uchirilganda hisob-kitoblar
     * **/
    // paid_change_sum();
    function item_change_sum(total, paid, debt, discount) {

        if((paid == 0 || paid > 0) && debt == 0 && discount == 0) {
            paid = total;
            debt = 0;
        } else if (debt > 0 && discount == 0) {
            debt = total - paid;
        } else if (debt > 0 && discount > 0) {
            if(discount < debt) {
                debt = debt - discount;
            } else {
                debt = 0;
                paid = total - discount;
            }
        } else if (paid > 0 && debt == 0) {
            paid = total - discount;
        }

        return {total:total, paid:paid, debt:debt};

    }

    /** *
     * 11. Tulandi field uzgarganda hisob-kitoblar
     * **/
    function paid_change_sum(total, paid, discount) {

        var debt = (total - discount) - paid;
		debt = debt > 0 ? debt : 0;

        return debt;
    }

    /**
     * 12. Ishi bitgan bemorning bugun qilgan tulovi va uning ruyhati
     * **/
    function get_selected_items_list(type, list) {

        var type_title = {'doctors':'Докторлар', 'labs':'Лаборатория', 'uzis':'УЗИ', 'services':'Қўшимча хизматлар'};
        var total = 0;
        var block = '<ul class="list-group list-group-sm">\n' +
            '                            <li class="list-group-item d-flex justify-content-between align-items-center active">'+type_title[type]+'</li>';

        $.each(list, function (index, item) {
            if(type == "doctors") {
                list[index]["name"] = item["last_name"] + " " + item["first_name"] + " ("+item["doctor_type"]+")";
            }

            total +=parseInt(item.price);
            block +='<li class="list-group-item d-flex justify-content-between align-items-center"> '+item.name+
                '<span class="badge badge-primary badge-pill">'+item.price+' сум</span>\n' +
                '</li>';
        });
        block +='<li class="list-group-item d-flex justify-content-between align-items-center bg-secondary text-white">\n' +
        '                                <strong>Жами</strong>\n' +
        '                                <strong>'+total+' сум</strong>\n' +
        '                            </li>\n' +
        '                        </ul>';

        return block;

    }

    /**
     * 13. Tugilgan kunni formatlash
     * **/
    function get_dob(dob_date) {

        const date = new Date(dob_date);
        var day = date.getDate();
        var month = date.getUTCMonth();
        var year = date.getFullYear();

        var days_arr = ["00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31"];
        var month_arr = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        // var full_date = days_arr[day] + "." + month_arr[month] + "." + year;
        var birthyear = year;

        return birthyear;

    }

    function get_count_of_days (start_date, end_date) {
		let start = moment(start_date, "DD.MM.YYYY");
		let end = moment(end_date, "DD.MM.YYYY");
		let days = moment.duration(end.diff(start)).asDays();

		return days;
	}

    /** Funksiyalar tugadi **/


/**********************************************
 * ***********
 * Eventlar boshlandi
 * ***********
 * ********************************************/

    /**** Checkboxlarni uzgartirganda *****/
    $(document).on("change",".js_select_item", function () {
        var $this = $(this);
        var modal_content = $this.closest(".js_items_content");
        var total = summarize_all_selected(modal_content);
        selected_items_menu_hide_show($this, modal_content);
        yigindi_block_hide_show(total, "items");
        bg_image_hide_show(total, modal_content);
        if(total == 0) {
			clear_payment_inputs();
		}
    });

    /**** Chegirma turlarini tanlaganda ****/
	$(".js_discount_type").change(function () {
		let $this = $(this);
		let yigindi_block 	= $this.closest(".yigindi_block");
		let discount_dom 	= yigindi_block.find(".js_discount");
		let discount_value_dom	= yigindi_block.find(".js_discount_value");

		discount_value_dom.val(0);
		discount_field_change(discount_value_dom);
		if($this.val() == '') {
			let total   = $(".js_selected_items_sum_total").find("#total").val();
			let debt    = $(".js_selected_items_sum_qarzingiz").find("#debt").val();
			let discount= discount_value_dom.val();
			var paid    = total - debt - discount;
			$(".js_selected_items_sum_tulandi").find("#paid").val(paid);

			discount_dom.addClass("d-none");
		} else {
			$(".js_discount").removeClass("d-none");
		}

	});

	$(".js_discount_value").focusout(function () {
		var $this    = $(this);
		var yigindi_block = $this.closest(".yigindi_block");
		var payment_types_content = yigindi_block.find(".js_selected_items_payment_type");
		var paid_dom 	= yigindi_block.find("#paid");

		discount_field_change($this);
		get_by_cash(payment_types_content, paid_dom);

	});

	function discount_field_change(discount_value_dom) {

		let $this    		= discount_value_dom;
		let modal_content 	= $this.closest(".js_items_content");
		let discount_dom 	= $this;
		let discount_type 	= modal_content.find(".js_discount_type").val();
		let discount 		= discount_dom.val();
		let discount_value_percent_dom = $this.closest(".js_discount").find(".js_discount_value_percent");

		let debt_dom = modal_content.find(".js_selected_items_sum_qarzingiz").find("#debt");
		let paid_dom = modal_content.find(".js_selected_items_sum_tulandi").find("#paid");

		let by_cash_dom = modal_content.find(".yigindi_block").find("#by_cash");
		let by_card_dom = modal_content.find(".yigindi_block").find("#by_card");
		let by_bank_dom = modal_content.find(".yigindi_block").find("#by_bank");

		let total = modal_content.find(".js_selected_items_sum_total").find("#total").val();
		let debt = debt_dom.val();
		let paid = paid_dom.val();

		if(discount_type == 2) {
			let discount_old = discount;
			discount = (discount / 100) * total;
			let $pHtml = total +" * " + discount_old + "% = " + discount
			discount_value_percent_dom.html($pHtml);
			discount_value_percent_dom.removeClass("d-none");
		} else {
			discount_value_percent_dom.addClass("d-none");
		}

		if(parseInt(discount) > parseInt(total)) {
			$this.css("border", "1px solid red");
			alert("Чегирма суммаси тўлов суммасидан ортиб кетти, текшириб қайта киритинг!");
		} else {
			$this.removeAttr("style");
			if(discount == "") {
				discount = 0;
				paid = total - debt;
			}

			if(discount >= debt) {
				debt = 0;
				paid = total - discount;
			} else if(discount < debt) {
				debt = parseInt(total) - (parseInt(paid) + parseInt(discount));
			} else {
				paid = paid - discount;
			}

			by_cash_dom.val(total);
			by_card_dom.val(0);
			by_bank_dom.val(0);

			debt_dom.val(debt);
			paid_dom.val(paid);
		}


	}

    function hide_show_blocks($this) {
        var modal_content = $this.closest(".js_items_content");
        var selected_item_block_class = $this.closest(".list-group").parent();
        var item_id_text = "js_doctor_item_";

        if(selected_item_block_class.hasClass("js_selected_labs")) {
            item_id_text = "js_lab_item_";
        }

        if(selected_item_block_class.hasClass("js_selected_uzis")) {
            item_id_text = "js_uzi_item_";
        }

        if(selected_item_block_class.hasClass("js_selected_services")) {
            item_id_text = "js_service_item_";
        }

        if(selected_item_block_class.hasClass("js_selected_rooms")) {
            item_id_text = "js_room_item_";
        }

        var el = $this.closest("li");
        var el_id = el.data("id");
        var item_id = item_id_text + el_id;
        var selected_checkbox = modal_content.find("#"+item_id);

        selected_checkbox.prop("checked", false);

        var total = summarize_all_selected(modal_content);
        if(total == 0) {
            clear_payment_inputs();
        }
        selected_items_menu_hide_show(selected_checkbox);
        yigindi_block_hide_show(total, "items");
        bg_image_hide_show(total, modal_content);
    }

    /**** Tanlangan itemlarni x ni bosib close qilganda ****/
    $(document).on("click",".js_close_selected_item", function () {
        var $this = $(this);
        hide_show_blocks($this);

    });

    /**** Tulandi field uzgarganda qarzni chiqarib beradi ****/
    $(".js_selected_items_sum_tulandi #paid").focusout(function () {

        let $this 	= $(this);
		let modal_content = $this.closest(".js_items_content");
		let total 	= summarize_all_selected(modal_content);
        var paid 	= $this.val();

        if(paid > total) {
			// $this.closest(".js_selected_items_sum_tulandi").find("small.text-danger").html("qiymat jamidan katta bulishi mumkin emas");
			$this.css("border", "1px solid red");
			alert("Тўлов суммаси умумий суммасидан ортиб кетти, текшириб қайта киритинг!");
		} else {
			// $this.closest(".js_selected_items_sum_tulandi").find("small.text-danger").html("");

			$this.removeAttr("style");
		}

		let debt_dom 	= modal_content.find(".js_selected_items_sum_qarzingiz").find("#debt");
		let by_cash_dom = modal_content.find(".js_selected_items_payment_type").find("#by_cash");
		let by_card_dom = modal_content.find(".js_selected_items_payment_type").find("#by_card");
		let by_bank_dom = modal_content.find(".js_selected_items_payment_type").find("#by_bank");

		let discount_type  = modal_content.find(".js_selected_items_discount").find(".js_discount_type").val();
        var discount_value = modal_content.find(".js_discount").find("#discount_value").val();
        var discount 	   = discount_value;
        if(discount_type == 2) {
            discount = (discount_value / 100) * total;
        }

        var debt = paid_change_sum(total, paid, discount);

        debt_dom.val(debt);
		by_cash_dom.val(total - debt - discount);
		by_card_dom.val(0);
		by_bank_dom.val(0);

    });

    /**
     * Bemorlarning tulov qilingan narsalarni kursatish
     * */
    $(".js_show_payment_items").click(function (e) {
		e.preventDefault();

        let $this       = $(this);
		let payment_id  = $this.data("paymentId");
		let patient_id  = $this.data("id");
		let service_type = $this.data("serviceType");
		let table_type 	= $this.data("tableType");
		let incomplete_patients_modal = $("#patient_selected_items");
        if(service_type == "room") {
            incomplete_patients_modal = $("#patient_selected_room");
        }

        var selected_docs = "";
        var selected_labs = "";
        var selected_uzis = "";
        var selected_services = "";
        var selected_beds = "";
        var doctor_label = "";
        var lab_label = "";
        var uzi_label = "";
        var service_label = "";
        var url = $this.data("url"); //"/hospitalzm/admin/registry/ajax_selected_items"

        $.post(url, {payment_id:payment_id, table_type: table_type}, function (res) {
            incomplete_patients_modal.find("form").find("input[name='payment_id']").val(payment_id);
            incomplete_patients_modal.find("form").find("input[name='patient_id']").val(patient_id);
            incomplete_patients_modal.find(".js_form").find(".checkbox").css("background-color", "");

            var selected_partner_id = res["payments"]["partner_id"];
            var selected_sender_doctor_id = res["payments"]["doctor_id"];
            var partners_options_dom = incomplete_patients_modal.find("form").find("[name='partner_id']");
            partners_options_dom.val(selected_partner_id);
            partners_options_dom.trigger('change');

            var sender_doctor_options_dom = incomplete_patients_modal.find("form").find("[name='sender_doctor_id']");
			sender_doctor_options_dom.val(selected_sender_doctor_id);
			sender_doctor_options_dom.trigger('change');

            if(res.doctors.length > 0) {
                $.each(res.doctors, function (index, doctor) {
                    let doct = $(".js_form_doctors #js_doctor_item_" + doctor["doctor_id"]);
					let checkbox_block_dom 	= doct.closest(".checkbox");
					let item_count_value_input_dom = checkbox_block_dom.find(".js_item_count_value_input");
					let item_count_value_text_dom = checkbox_block_dom.find(".js_item_count_value_text");
					var item_count = doctor["count"];
					var item_price = doctor["price"];

					item_count_value_input_dom.val(item_count)
					item_count_value_text_dom.html(item_count)

					doct.prop("checked", true);
					checkbox_block_dom.removeClass("bg-light").addClass("bg-info text-white");



                    if(doctor.status == 1) {
                        doct.attr("disabled", true);
                    }

                    //agar status = 2 bulsa, yani shifokor bemorni tulovga yuborgan bulsa
					//yoki qarzi tulangan bulsa
                    var service_completed_status = doctor["status"];
                    if(res.payments["status"] == 2 || res.hide_services_col) {
                        service_completed_status = 1;
                    }
                    doctor_label = doctor["last_name"] + " " + doctor["first_name"] + " ("+doctor["doctor_type"]+")";
                    selected_docs += selected_item(doctor["doctor_id"], doctor_label, service_completed_status, item_count, item_price);
                });

                var js_form_doctors = incomplete_patients_modal.find(".js_form_doctors");
                var selected_doctors_block = incomplete_patients_modal.find(".js_selected_doctors");
                incomplete_patients_modal.find(".js_nav_doctor_tab").text(res.doctors.length);
                selected_doctors_block.removeClass("d-none");
                selected_doctors_block.find("ul").removeClass("d-none");

                var doctor_total = summarize_one_block("js_form_doctors", incomplete_patients_modal);
                selected_doctors_block.find(".js_total_sum--tulovga").html("Жами: "+doctor_total);
                // summarize_selected_items(js_form_doctors, selected_doctors_block);

                $(".js_selected_doctors .js_selected_items_title").after(selected_docs);

            } else {
                incomplete_patients_modal.find(".js_nav_doctor_tab").text("");
            }

            if(Object.keys(res.labs).length > 0) {
                $.each(res.labs, function (index, lab) {
                    var labf = $(".js_form_labs #js_lab_item_" + lab["lab_id"]);

					let checkbox_block_dom 	= labf.closest(".checkbox");
					let item_count_value_input_dom = checkbox_block_dom.find(".js_item_count_value_input");
					let item_count_value_text_dom = checkbox_block_dom.find(".js_item_count_value_text");
					let item_count = lab["count"];
					let item_price = lab["price"];

					item_count_value_input_dom.val(item_count)
					item_count_value_text_dom.html(item_count)

					labf.prop("checked", true);
					checkbox_block_dom.removeClass("bg-light").addClass("bg-info text-white");

					if(lab["status"] == 1) {
                        labf.attr("disabled", true);
                    }

                    //agar status = 2 bulsa, yani shifokor bemorni tulovga yuborgan bulsa
                    var service_completed_status = lab["status"];
                    if(res.payments["status"] == 2) {
                        service_completed_status = 1;
                    }
                    lab_label = lab["name"];
                    selected_labs += selected_item(lab["lab_id"], lab_label, service_completed_status, item_count, item_price);
                });

                var js_form_labs = incomplete_patients_modal.find(".js_form_labs");
                var selected_labs_block = incomplete_patients_modal.find(".js_selected_labs");
                incomplete_patients_modal.find(".js_nav_lab_tab").text(Object.keys(res.labs).length);
                selected_labs_block.removeClass("d-none");
                selected_labs_block.find("ul").removeClass("d-none");
                var lab_total = summarize_one_block("js_form_labs", incomplete_patients_modal);
                selected_labs_block.find(".js_total_sum--tulovga").html("Жами: "+lab_total);

                $(".js_selected_labs .js_selected_items_title").after(selected_labs);
            } else {
                incomplete_patients_modal.find(".js_nav_lab_tab").text("");
            }

            if(res.uzis.length > 0) {
                $.each(res.uzis, function (index, uzi) {
                    var uzif = $(".js_form_uzis #js_uzi_item_" + uzi["uzi_id"]);

					let checkbox_block_dom 	= uzif.closest(".checkbox");
					let item_count_value_input_dom = checkbox_block_dom.find(".js_item_count_value_input");
					let item_count_value_text_dom = checkbox_block_dom.find(".js_item_count_value_text");
					let item_count = uzi["count"];
					let item_price = uzi["price"];

					item_count_value_input_dom.val(item_count)
					item_count_value_text_dom.html(item_count)

					uzif.prop("checked", true);
					checkbox_block_dom.removeClass("bg-light").addClass("bg-info text-white");

                    if(uzi.status == 1) {
                        uzif.attr("disabled", true);
                    }

                    //agar status = 2 bulsa, yani shifokor bemorni tulovga yuborgan bulsa
                    var service_completed_status = uzi["status"];
                    if(res.payments["status"] == 2) {
                        service_completed_status = 1;
                    }
                    uzi_label = uzi["name"];
                    selected_uzis += selected_item(uzi["uzi_id"], uzi_label, service_completed_status, item_count, item_price);
                });

                var js_form_uzis = incomplete_patients_modal.find(".js_form_uzis");
                var selected_uzis_block = incomplete_patients_modal.find(".js_selected_uzis");
                incomplete_patients_modal.find(".js_nav_uzi_tab").text(res.uzis.length);
                selected_uzis_block.removeClass("d-none");
                selected_uzis_block.find("ul").removeClass("d-none");
                // summarize_selected_items(js_form_uzis, selected_uzis_block);

                $(".js_selected_uzis .js_selected_items_title").after(selected_uzis);
            } else {
                incomplete_patients_modal.find(".js_nav_uzi_tab").text("");
            }

            if(res.services.length > 0) {
                $.each(res.services, function (index, service) {
                    var servicef = $(".js_form_services #js_service_item_" + service["service_id"]);
					let checkbox_block_dom 	= servicef.closest(".checkbox");
					let item_count_value_input_dom = checkbox_block_dom.find(".js_item_count_value_input");
					let item_count_value_text_dom = checkbox_block_dom.find(".js_item_count_value_text");
					var item_count = service["count"];
					var item_price = service["price"];

					item_count_value_input_dom.val(item_count)
					item_count_value_text_dom.html(item_count)

					servicef.prop("checked", true);
					checkbox_block_dom.removeClass("bg-light").addClass("bg-info text-white");

                    if(service.status == 1) {
                        servicef.attr("disabled", true);
                    }

                    //agar status = 2 bulsa, yani shifokor bemorni tulovga yuborgan bulsa
                    var service_completed_status = service["status"];
                    if(res.payments["status"] == 2) {
                        service_completed_status = 1;
                    }
                    service_label = service["name"];
                    selected_services += selected_item(service["service_id"], service_label, service_completed_status, item_count, item_price);
                });

                var js_form_services = incomplete_patients_modal.find(".js_form_services");
                var selected_services_block = incomplete_patients_modal.find(".js_selected_services");
                incomplete_patients_modal.find(".js_nav_service_tab").text(res.services.length);
                selected_services_block.removeClass("d-none");
                selected_services_block.find("ul").removeClass("d-none");
                // summarize_selected_items(js_form_uzis, selected_uzis_block);

                $(".js_selected_services .js_selected_items_title").after(selected_services);
            } else {
                incomplete_patients_modal.find(".js_nav_service_tab").text("");
            }

            if(Object.keys(res.rooms).length > 0) {

				let bed = res.rooms.this_room;
				let start_date  = change_date_format(bed["start_date"], "DD.MM.YYYY");
				let end_date    = change_date_format(bed["end_date"], "DD.MM.YYYY");

                /*******************/
				var bedf = $(".js_form_rooms #js_room_item_" + bed["bed_id"]);
				let checkbox_block_dom 	= bedf.closest(".checkbox");
				let item_count_value_input_dom = checkbox_block_dom.find(".js_item_count_value_input");
				let item_count_value_text_dom = checkbox_block_dom.find(".js_item_count_value_text");
				let item_price = bed["bed_price"];
				let item_count = get_count_of_days(start_date, end_date);

				item_count_value_input_dom.val(item_count)
				item_count_value_text_dom.html(item_count)

				bedf.prop("checked", true);
				checkbox_block_dom.removeClass("bg-light").addClass("bg-info text-white");
                /*******************/
				bedf.closest(".js_form_rooms").find(".checkbox input[type='radio']").attr("disabled", false);

				let start_date_dom 	= incomplete_patients_modal.find(".js_room_date_start");
				let end_date_dom 	= incomplete_patients_modal.find(".js_room_date_end");

                start_date_dom.val(start_date);
                end_date_dom.val(end_date);

                //compare dates
				let t_date_obj = new Date();
				let today = t_date_obj.getTime();

				let date_obj = new Date(bed["start_date"]);
				let s_date_obj = new Date(date_obj.getFullYear(), date_obj.getMonth(), date_obj.getDate(), 23, 59, 59, 0);
				let sDate = s_date_obj.getTime();

                var bed_label = "Хона: " + bed["room_number"] + " / Ётоқ: " + bed["bed_name"];
                selected_beds += selected_item(bed["bed_id"], bed_label, 1, item_count, item_price);

                incomplete_patients_modal.find(".js_form_rooms");
                var selected_rooms_block = incomplete_patients_modal.find(".js_selected_rooms");
                incomplete_patients_modal.find(".js_nav_room_tab").text(1);
                selected_rooms_block.removeClass("d-none");
                selected_rooms_block.find("ul").removeClass("d-none");
                var room_total = summarize_one_block("js_form_rooms", incomplete_patients_modal);
                selected_rooms_block.find(".js_total_sum--tulovga").html("Жами: " + room_total);

                $(".js_selected_rooms .js_selected_items_title").after(selected_beds);

				$.each(res.rooms.selected_rooms, function (index, room) {

					if(bed["bed_id"] != room["bed_id"]) {
						var bed_f = $("#js_room_item_" + room["bed_id"]).attr("disabled", true);
					} else {
						checkbox_block_dom.removeClass("bg-danger").addClass("bg-info text-white");
					}

				});

            }

            var total = summarize_all_selected(incomplete_patients_modal);

            var payments = res.payments;
            yigindi_block_hide_show(total, "items", payments);

            let modal_content = incomplete_patients_modal;

            /*************************************************************************************/
			let paid_dom = modal_content.find(".js_completed_paid");
			let debt_dom = modal_content.find(".js_completed_debt");
			let discount_dom = modal_content.find(".js_completed_discount");
			let total_dom = modal_content.find(".js_completed_total");
			let sum_label = "сум";
			let percent_label = "%";
			let patient_name_dom = modal_content.find(".js_completed_patient_name");
			let patient_dob_dom = modal_content.find(".js_completed_patient_dob");
			let patient_address_dom = modal_content.find(".js_completed_patient_address");

            paid_dom.html(res["payments"]["paid"] +' '+sum_label);
            debt_dom.html(res["payments"]["debt"] +' '+sum_label);
            total_dom.html(res["payments"]["total"] +' '+sum_label);

            var discount = res["payments"]["discount_value"];
            if(res["payments"]["discount_type"] == 2) {
                discount = discount + " " + percent_label;
            } else {
				discount = discount + " " + sum_label;
			}

            discount_dom.html(discount);

            patient_name_dom.html(res["patient"]["last_name"] + " " +res["patient"]["first_name"]);
            var dob = res["patient"]["dob"] === null ? '':get_dob(res["patient"]["dob"]);
            patient_dob_dom.html(dob);
            patient_address_dom.html(res["patient"]["region_name"] + ", " + res["patient"]["city_name"] + ", " + res["patient"]["address"]);

            /*************************************************************/

            modal_content.find(".modal-header").find("#bemor_id").text(res.patient.username);
            modal_content.find(".modal-header").find("#cheque").text(payment_id);
            modal_content.find(".modal-header").find("#ism").text(res.patient.last_name + " " + res.patient.first_name);
            modal_content.find(".modal-header").find("#yosh").text(get_dob(res["patient"]["dob"]));
            modal_content.find(".modal-header").find("#tel").text(res.patient.phone);

            //agar tulov tugatilgan bulsa yoki qarz tulangan bulsa bu datatabledan xizmatlarni yashirib quyamiz
			hide_services_col(res.hide_services_col, incomplete_patients_modal);

            incomplete_patients_modal.modal("show");

        }, "json")
    });

	//agar tulov tugatilgan bulsa yoki qarz tulangan bulsa bu datatabledan xizmatlarni yashirib quyamiz
    function hide_services_col(hide, modal_dom) {
		let items_content_dom 			= modal_dom.find(".js_items_content");
		let items_content_right_dom 	= items_content_dom.find(".js_items_content_right");
		let items_content_left_dom 		= items_content_dom.find(".js_items_content_left");
		let yigindi_block_container_dom = items_content_left_dom.find(".js_yigindi_block_container");
		let items_list_dom 				= items_content_left_dom.find(".js_items_list");
		if(hide) {
			items_content_right_dom.addClass("d-none");
			items_content_left_dom.removeClass("col-lg-5").addClass("col-lg-12");
			yigindi_block_container_dom.removeClass("col-sm-12").addClass("col-sm-6");
			items_list_dom.removeClass("col-sm-12").addClass("col-sm-6");
		} else {
			items_content_right_dom.removeClass("d-none");
			items_content_left_dom.removeClass("col-lg-12").addClass("col-lg-5");
			yigindi_block_container_dom.removeClass("col-sm-6").addClass("col-sm-12");
			items_list_dom.removeClass("col-sm-6").addClass("col-sm-12");
		}
	}

    $('#patient_selected_items,#patient_selected_room').on('hidden.bs.modal', function (e) {

        var $this = $(this);
        var selected_items = $this.find("input.js_select_item:checked");
        // var selected_items = $this.find("input[type='checkbox']:checked");
        $.each(selected_items, function (index, checkbox) {

            $(checkbox).prop("checked", false);
            $(checkbox).prop("disabled", false);
			$(checkbox).closest(".checkbox").removeClass("bg-info text-white").addClass("bg-light");
        });

        $this.find("form").find("input[name='payment_id']").val('');
        $this.find("form").find("input[name='patient_id']").val('');

        var selected_items_menu = $(".js_selected_items_menu");
        selected_items_menu.find(".js_items_list").children().addClass("d-none");
        $this.find(".js_selected_item").remove();
    });

    $(document).on("click",".js_save_print",function (e) {
        e.preventDefault();
		let $this = $(this);
		let $modal = $this.closest(".modal");
		let form = $this.closest("form");
		let print_cheque = $this.data("printCheque")
		let selected_items = get_selected_items($this);

        if(form.find(".js_room_date").length > 0) {
            selected_items["room_start_date"] = form.find(".js_room_date_start").val();
            selected_items["room_end_date"]   = form.find(".js_room_date_end").val();
        }

        var service_types = "";
        service_types += (Object.keys(selected_items.doctors).length > 0 ? "D":"");
        service_types += (Object.keys(selected_items.labs).length > 0 ? " L":"");
        service_types += (Object.keys(selected_items.uzis).length > 0 ? " U":"");
        service_types += (Object.keys(selected_items.services).length > 0 ? " X":"");

        var url = $this.data("url"); //admin/registry/ajax_update_selected_items

        $.post(url, {"selected_items":selected_items, "print_cheque":print_cheque}, function (res) {
            // if(selected_items.total == 0 || res.payment_status == 'completed') {
            if(selected_items.total == 0) {
                $(".js_payments_"+selected_items.payment_id).closest("tr").remove();
            } else {
                let payments = selected_items.paid + "/"+ selected_items.total;
                let summa_content = $(".js_payments_"+selected_items.payment_id);
                summa_content.html(payments);
                summa_content.closest(".js_show_payment_items").find(".text-dark").html(service_types);
                let partner_name = res.payment.partner_last_name +' '+ res.payment.partner_first_name;
                if(res.payment.partner_id == 0){

                 	partner_name = res.payment.sender_doctor_last_name +' '+ res.payment.sender_doctor_first_name;

				}
                $("#js_row_"+selected_items.payment_id).find(".js_partner").html(partner_name);
            }

            $modal.modal("hide");
            if(form.find(".js_room_date").length > 0) {
                window.location.reload();
            }

        }, "json");


    });

    /**
     * Bugungi tugallangan bemorlarning tulov qilingan narsalarni kursatish
     * */
    $(".js_show_completed_payment_items").click(function () {
        let $this = $(this);
        let payment_id = $this.data("paymentId");
        let patient_id = $this.data("id");
        let completed_patients_modal = $("#patient_completed");
        let selected_docs_block = completed_patients_modal.find(".js_completed_doctors");
        let selected_labs_block = completed_patients_modal.find(".js_completed_labs");
        let selected_uzis_block = completed_patients_modal.find(".js_completed_uzis");
        let selected_services_block = completed_patients_modal.find(".js_completed_services");
        var selected_doctors = "";
        var selected_labs = "";
        var selected_uzis = "";
        let paid_dom = completed_patients_modal.find(".js_completed_paid");
        let debt_dom = completed_patients_modal.find(".js_completed_debt");
        let discount_dom = completed_patients_modal.find(".js_completed_discount");
        let total_dom = completed_patients_modal.find(".js_completed_total");
        let sum_label = "сум";
        let percent_label = "%";
        let patient_name_dom = completed_patients_modal.find(".js_completed_patient_name");
        let patient_dob_dom = completed_patients_modal.find(".js_completed_patient_dob");
        let patient_address_dom = completed_patients_modal.find(".js_completed_patient_address");
        let url = $this.data("url");// admin/registry/ajax_selected_items

        $.post(url, {payment_id:payment_id}, function (res) {

            paid_dom.html(res["payments"]["paid"] +' '+sum_label);
            debt_dom.html(res["payments"]["debt"] +' '+sum_label);
            total_dom.html(res["payments"]["total"] +' '+sum_label);
            var discount = "0 сум";
            if(res["payments"]["discount_sum"] > 0) {
                discount = res["payments"]["discount_sum"] + " " + sum_label;
            }

            if(res["payments"]["discount_percent"] > 0) {
                discount = res["payments"]["discount_percent"] + ' '+percent_label;
            }
            discount_dom.html(discount);

            patient_name_dom.html(res["patient"]["last_name"] + " " +res["patient"]["first_name"]);

            var dob = res["patient"]["dob"] === null ? '':get_dob(res["patient"]["dob"]);
            patient_dob_dom.html(dob);
            patient_address_dom.html(res["patient"]["region_name"] + ", " + res["patient"]["city_name"] + ", " + res["patient"]["address"]);

            if(res.doctors.length > 0) {
                selected_docs_block.removeClass("d-none");
                selected_doctors = get_selected_items_list("doctors", res["doctors"]);
                selected_docs_block.html(selected_doctors);
            } else {
                selected_docs_block.html("");
            }

            if(Object.keys(res.labs).length > 0) {
                selected_labs_block.removeClass("d-none");
                selected_labs = get_selected_items_list("labs", res["labs"]);
                selected_labs_block.html(selected_labs);
            } else {
                selected_labs_block.html("");
            }

            if(res.uzis.length > 0) {
                selected_uzis_block.removeClass("d-none");
                selected_uzis = get_selected_items_list("uzis", res["uzis"]);
                selected_uzis_block.html(selected_uzis);
            } else {
                selected_uzis_block.html("");
            }

            if(res.services.length > 0) {
                selected_services_block.removeClass("d-none");
                selected_services = get_selected_items_list("services", res["services"]);
                selected_services_block.html(selected_services);
            } else {
                selected_services_block.html("");
            }

            completed_patients_modal.modal("show");

        }, "json")
    });

    /**
    * Bemorlarning qarzlarini yopish
    * url = ajax_payoff_debt
    * */
    $(".js_payoff_debt").click(function () {
        var $this = $(this);
        var url = $this.data("url");
        var payment_id = $this.data("paymentId");

        $.post(url, {"payment_id":payment_id}, function (res) {
            $this.closest("tr").remove();
        }, "json");
    });

    /**
    * Bemoring qarzini kurish
    * */
    $(".js_show_debt").click(function () {

        var $this 	= $(this);
        var url 	= $this.data("url"); //admin/registry/ajax_show_debt

        var payment_id = $this.data("paymentId");
		var modal_content = $("#debt_details");

        $.post(url, {"payment_id":payment_id}, function (res) {
			modal_content.find(".modal-body").html(res);
        	modal_content.modal("show");
        }, "json");
    });

    /***
    * Qarzini tulash
    * */
    $(document).on("click",".js_payoff_debt_btn", function (e) {
		e.preventDefault();
		var $this 	= $(this);
		var form 	= $this.closest("form");
		var $modal 	= $this.closest("#debt_details");
		var form_data = form.serializeArray();
		var url 	= form.attr("action"); //admin/registry/ajax_payoff_debt

		if(validate_debt_form($this)) {
            $.post(url, form_data, function (res) {

              var payment_table = $modal.find(".js_payment_table");
                var payment_details_table = $modal.find(".js_payment_details_table");

                payment_details_table.find("tbody").append(res["html"]);
                payment_table.find(".js_patient_paid").html(res["paid_total"]);
                payment_table.find(".js_patient_debt span").html(res["debt"]);

                form.find("#by_cash").val(0);
                form.find("#by_card").val(0);
                form.find("#by_bank").val(0);

                setTimeout(function () {
                    $("#debt_details").modal("hide");
                    location.reload();
                }, 1000);
			}, "json");
		}

	});

	/**
	 * Qarz tulov formalarini tuldirganda validate qilish function
	 * */
	function validate_debt_form($this) {
		var form 		= $this.closest("form");
		var $modal 		= $this.closest("#debt_details");
        var patient_debt= parseInt(form.find("#patient_debt").val());
        var total_debt  = 0;
		var service_debt_row_block = form.find(".js_service_debt_row_block");
		var my_return = true;
		$.each(service_debt_row_block, function (index, debt_row_block) {
            var debt_row = $(debt_row_block).find(".js_service_debt_row");
            var row_by_cash_dom  = $(debt_row).find(".js_by_cash");
            var row_by_card_dom  = $(debt_row).find(".js_by_card");
            var row_by_bank_dom  = $(debt_row).find(".js_by_bank");
            var row_debt_summa   = $(debt_row).find(".js_service_debt").data("serviceDebt");

            var row_by_cash = row_by_cash_dom.val() == '' ? 0:parseInt(row_by_cash_dom.val());
            var row_by_card = row_by_card_dom.val() == '' ? 0:parseInt(row_by_card_dom.val());
            var row_by_bank = row_by_bank_dom.val() == '' ? 0:parseInt(row_by_bank_dom.val());
            var row_summa = row_by_cash + row_by_card + row_by_bank;

                total_debt += row_summa;

            var error_msg = "Тўлов суммасини киритинг!!!";
            $(debt_row_block).find("small").html("");

            if(row_summa > row_debt_summa) {
                error_msg = "Тўлов қиймати қарзнинг қийматидан ошиб кетти, илтимос текшириб кўринг!!!";
                $(debt_row_block).find("small").html(error_msg);

                my_return = false;
            }
        });

        form.find(".js_total_debt_error_message small").html("");
        error_msg = "";
		if(my_return) {
            if (total_debt == 0) {
                error_msg = "Тўлов суммасини киритинг!!!";

                my_return = false;
            } else if(total_debt > patient_debt) {
                error_msg = "Умумий тўлов қиймати умумий қарзнинг қийматидан ошиб кетти, илтимос текшириб кўринг!!!";

                my_return = false;
            } else {

                my_return = true;
            }

            form.find(".js_total_debt_error_message small").html(error_msg);
        }

        return my_return;
    }



	/**********************************************
	* Qarzlarni yopish funksiya va eventlar tugadi
	* *********************************************/

    /**
     * Bemorni uchirish. Delete tugmasini bosilganda, patient ga tegishli bulgan barcha ma'lumotlar uchadi
     * doctor kurigi, laboratoriya analizlari, UZI analizlari va bemorning uzi xaqidagi ma'lumotlar ham.
     * **/
    $(".js_delele_patient").click(function () {
        var $this = $(this);
        var url = $this.data("href");

        var patient_id = $this.data("patientId");
        var message = "Ушбу маълумотни хақиқатан хам ўчирасизми?";
        show_notification_modal(url, patient_id, false, message);
    });

    //Tulovni otmen qilish
    $(".js_cancel_payment").click(function () {
        var $this = $(this);
        var url = $this.data("href");
        var patient_id = $this.data("patientId");
        var payment_id = $this.data("paymentId");
        var message = "Ушбу тўловни хақиқатан хам бекор қиласизми?";
        show_notification_modal(url, patient_id, payment_id, message);

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
        var row = $("#js_row_"+payment_id);
        if(!payment_id) {
            row = $("#js_row_"+patient_id);
        }
        var text = "";


        $.post(url, {patient_id:patient_id, payment_id:payment_id}, function (res) {
            if(res.action == "deleted") {
                text = "<i class='fa fa-info-circle fa-2x text-primary'></i> Маълумот муваффақиятли ўчирилди!";
                $modal.find(".modal-body").html(text);
                $modal.modal("hide");
                row.remove();
            } else if(res.action == "canceled") {
                if(res.cancel){
                    $modal.find(".modal-body").html("");
                    $modal.modal("hide");
                    row.remove();
                    window.location.reload();
                } else {
                    $modal.find(".modal-body").html("Бу тўловни бекор қилиб бўлмайди. Кўриг давом этмоқда!");
                    $modal.find(".modal-footer .js_buttons_confirm").addClass("d-none");
                    $modal.find(".modal-footer .js_button_close").removeClass("d-none");
                }
            }
            changeStatus();
        }, "json");
    });


    if($("#confirm_delete_notifier").length > 0) {
        var $modal = $("#confirm_delete_notifier");
        $modal.on('hidden.bs.modal', function () {
            $modal.modal('dispose');
            $modal.find(".modal-footer .js_buttons_confirm").removeClass("d-none");
            $modal.find(".modal-footer .js_button_close").addClass("d-none");
        });

    }

    /*****
    * Patient Medical History
    * */
    $(".js_patient_history_item").click(function () {
        var $this = $(this);
        var history_box = $this.closest(".js_patient_hisotry_box");
        var patient_hisotry_list = $this.closest(".js_patient_hisotry_list");
        var patient_history_card = $this.closest(".js_patient_history_card");
        var payment_date = patient_history_card.data("paymentDate");
        var patient_id = patient_history_card.data("patientId");
        var item_type = $this.data("itemType");
        var payment_id = $this.data("paymentId");
        let button_type = $this.data("buttonType");
        var url = patient_hisotry_list.data("url"); //admin/registry/ajax_medical_history

        patient_hisotry_list.find(".list-group-item button").removeClass("active");
        $this.addClass("active");

        var patient_hisotry_view = history_box.find(".js_patient_hisotry_view");
        var patient_hisotry_content = patient_hisotry_view.find(".js_patient_hisotry_content");

        $.post(url, {payment_date:payment_date, patient_id:patient_id, item_type:item_type, payment_id:payment_id, button_type:button_type}, function (res) {
            patient_hisotry_content.html(res);
        }, "json");
    });

    $(".js_complete_service").click(function () {
        var $this = $(this);
        var url = $this.data("url"); // url = "/admin/registry/ajax_patient_service_status"
        var payment_id = $this.data("paymentId");
        var service_status = $this.attr("id");
        var incomplete_table = $(document).find('#incomplete_patients');
        var incomplete = incomplete_table.DataTable();
        var completed_table = $(document).find('#completed_patients');
        var completed = completed_table.DataTable();
        var inc_row = $this.closest("#js_row_"+payment_id);

        $.post(url, {"payment_id":payment_id, service_status:service_status}, function (status) {

            move_row_datatable(incomplete, completed, inc_row);

        }, "json");
    });

    var registry_urls = $("#registry_urls");
    if(registry_urls.length > 0){
        var updateStatus = setInterval(changeStatus, 50000);
    }

    function changeStatus(){

        var update_payments_url = registry_urls.data("updatePaymentsUrl"); //admin/registry/ajax_update_payments
        var page = registry_urls.data("page");

        var today_incom_patiens_count_dom   = $(".js_today_incomplete_patients_count");
        var credit_incom_patiens_count_dom  = $(".js_credit_incomplete_patients_count");

        var incomplete_table = $(document).find('#incomplete_patients');
        var incomplete = incomplete_table.DataTable();
        var completed_table = $(document).find('#completed_patients');
        var completed = completed_table.DataTable();

        $.post(update_payments_url, {page:page}, function (res) {

            $.each(res.incomplete_patients, function (index, payment) {
                var row             = $("#js_row_"+payment.payment_id);
                var order_status    = row.find(".js_order_status");
                if(payment.status == 1) {

                } else if (payment.order_status == 1) {
                    order_status.removeClass("badge-info-border").addClass("badge-success-border").html("Шифокор қабулида");
                } else if (payment.order_status == 2) {
                    order_status.removeClass("badge-info-border").addClass("badge-success-border").html("Лабораторияда");
                } else if (payment.order_status == 3) {
                    order_status.removeClass("badge-info-border").addClass("badge-success-border").html("УЗИда");
                } else {
                    order_status.removeClass("badge-success-border").addClass("badge-info-border").html("Навбатда");
                }
            });

            $.each(res.completed_patients, function (payment_id, payment) {
                var inc_row = incomplete_table.find("#js_row_"+payment_id);
                if(inc_row.length > 0) {
                    move_row_datatable(incomplete, completed, inc_row);
                }
            });

            var today_count = res.patients_counts.today_count > 0 ? res.patients_counts.today_count:'';

            today_incom_patiens_count_dom.html(res.patients_counts.today_count);
            credit_incom_patiens_count_dom.html(res.patients_counts.credit_count);

        }, "json");
    }

    function move_row_datatable(table1, table2, row_dom) {
        row_dom.find(".js_order_status").removeClass("badge-info-border").removeClass("badge-success-border").addClass("badge-danger-border").text("Ёпилган");
        row_dom.find('.js_show_payment_items').removeClass('js_show_payment_items').addClass('js_show_completed_payment_items');

        var row = table1.row(row_dom);
        var rowNode = row.node();
        row.remove();

        table2.row.add(rowNode).draw();
        window.location.reload();
    }

	$("#by_card, #by_bank").focusout(function () {
		var $this = $(this);
		var payment_types_content = $this.closest(".js_selected_items_payment_type");
		var paid_dom 	= $this.closest(".yigindi_block").find("#paid");

		get_by_cash(payment_types_content, paid_dom);
	});

    //qolgan tulov turlarining qiymati uzgartirilganda, Naqd pulni xisoblash
    function get_by_cash(payment_types_content_dom, paid_dom) {
		var by_cash_dom = payment_types_content_dom.find("#by_cash");
		var by_card_dom = payment_types_content_dom.find("#by_card");
		var by_bank_dom = payment_types_content_dom.find("#by_bank");

		var paid 	= parseInt(paid_dom.val());
		var by_card = by_card_dom.val() != '' ? parseInt(by_card_dom.val()) : 0;
		var by_bank = by_bank_dom.val() != '' ? parseInt(by_bank_dom.val()) : 0;

		var by_cash = paid - (by_card + by_bank);
		by_cash_dom.val(by_cash);

	}

	if($('.js_room_date').length > 0) {
		$('.js_room_date').datetimepicker({
			format: 'DD.MM.YYYY',
		}).on("dp.hide", function(e){
			let $this = $(this);
			let date_content 	= $this.closest(".js_selected_items_date_range");
			let start_date_dom 	= date_content.find(".js_room_date_start");
			let end_date_dom 	= date_content.find(".js_room_date_end");

			const start_date 	= start_date_dom.val();
			const end_date 		= end_date_dom.val();
			let start = moment(start_date, "DD.MM.YYYY");
			let end = moment(end_date, "DD.MM.YYYY");
			let days = moment.duration(end.diff(start)).asDays();
			let error_msg_dom = date_content.find("small");
			error_msg_dom.html('');

			if(days > 0) {
				let modal_content = $this.closest(".js_items_content");
				let this_selected_item = modal_content.find(".js_form_rooms .js_select_item:checked");
				var total = summarize_all_selected(modal_content);
				selected_items_menu_hide_show(this_selected_item);
				yigindi_block_hide_show(total, "items");
				bg_image_hide_show(total, modal_content);
				if(total == 0) {
					clear_payment_inputs();
				}

                var room_total = summarize_one_block("js_form_rooms", modal_content);
                var selected_rooms_block = modal_content.find(".js_selected_rooms");
                selected_rooms_block.find(".js_total_sum--tulovga").html("Жами: " + room_total);

			} else {
				var msg = "Саналар оралиғини тўғри танланг";
				error_msg_dom.html(msg);
			}
		});
	}

	$(".js_show_service_items").click(function () {
        var $this = $(this);
        hide_show_blocks($this);

        var payment_id = $this.data("paymentId");
        var patient_id = $this.data("patientId");
        var $modal = $(document).find("#patient_selected_items");
        $modal.find("form").find("input[name='payment_id']").val(payment_id);
        $modal.find("form").find("input[name='patient_id']").val(patient_id);
        var checkboxes = $modal.find(".js_form").find(".checkbox");
        $.each(checkboxes, function (index, checkbox) {
            $(checkbox).prop("checked", false);
        });
        $modal.find(".js_form").find(".checkbox").css("background-color", "");
        $modal.modal("show");
    });

    $(document).on("click",".js_add_services_for_payment",function (e) {
        e.preventDefault();
        var $this = $(this);
        var $modal = $this.closest(".modal");
        var selected_items = get_selected_items($this);

        var url = $this.data("url"); //doctor/patients/ajax_add_selected_items

        if(Object.keys(selected_items["doctor_id"]).length > 0
            || Object.keys(selected_items["labs_id"]).length > 0
            || Object.keys(selected_items["uzis_id"]).length > 0
            || Object.keys(selected_items["services_id"]).length > 0
        ){
            $.post(url, {"selected_items":selected_items}, function (res) {
                $modal.modal("hide");
            }, "json");
        } else {
            $modal.find(".js_error_message").html("Камида битта хизматни танлашингиз керак!");
        }

    });

    //tanlangan barcha itemlarni olish
    function get_selected_items($this) {

		let form            = $this.closest("form");
		let js_form_doctors = $(".js_form_doctors");
		let js_form_labs    = $(".js_form_labs");
		let js_form_uzis    = $(".js_form_uzis");
		let js_form_services= $(".js_form_services");
		let js_form_rooms   = $(".js_form_rooms");

        let doctors 	= js_form_doctors.find("input.js_select_item:checked");
		let labs    	= js_form_labs.find("input.js_select_item:checked");
		let uzis    	= js_form_uzis.find("input.js_select_item:checked");
		let services	= js_form_services.find("input.js_select_item:checked");
		let rooms   	= js_form_rooms.find("input.js_select_item:checked");

		let doctors_values = {};
        $.each(doctors, function (index, doc) {
			var doc_id 	= $(doc).val();
			var count 		= $(doc).siblings(".js_item_count_value_input").val();
			doctors_values[index] = {"id":doc_id, "count":count};
        });

        var labs_values = {};
        $.each(labs, function (index, lab) {
			var lab_id 	= $(lab).val();
			var count 		= $(lab).siblings(".js_item_count_value_input").val();
			labs_values[index] = {"id":lab_id, "count":count};
        });

        var uzis_values = {};
        $.each(uzis, function (index, uzi) {
			var uzi_id 	= $(uzi).val();
			var count 		= $(uzi).siblings(".js_item_count_value_input").val();
			uzis_values[index] = {"id":uzi_id, "count":count};
        });

        var services_values = {};
        $.each(services, function (index, service) {
        	let service_id 	= $(service).val();
			let count 		= $(service).siblings(".js_item_count_value_input").val();
            services_values[index] = {"id":service_id, "count":count};

        });

        var rooms_values = {};
        $.each(rooms, function (index, bed) {
            rooms_values[index] = $(bed).val();
        });

        var selected_items = {
            "payment_id" : form.find('input[name="payment_id"]').val(),
            "patient_id" : form.find('input[name="patient_id"]').val(),
            "partner_id" : form.find('#partner_id').val(),
            "sender_doctor_id" : form.find('#sender_doctor_id').val(),
            "total"     : form.find('input[name="total"]').val(),
            "paid"      : form.find('input[name="paid"]').val(),
            "debt"      : form.find('input[name="debt"]').val(),
            "discount_type" : form.find('#discount_type').val(),
            "discount_value" : form.find('input[name="discount_value"]').val(),
            "by_cash"   : form.find('input[name="by_cash"]').val(),
            "by_card"   : form.find('input[name="by_card"]').val(),
            "by_bank"   : form.find('input[name="by_bank"]').val(),

            "doctors" 	: doctors_values,
            "labs"   	: labs_values,
            "uzis"   	: uzis_values,
            "services"  : services_values,
            "beds_id"   : rooms_values
        };

        return selected_items;
    }


    /**
     * Qarz yoki Chegirmalarni taqsimoti
     * **/
    $(".js_show_payment_debt_discount").click(function () {
        var $this               = $(this);
        var $modal              = $("#payment_debt_discount");
        var debt_discount_type  = $this.data("debtDiscountType");
        var parent_block        = $this.closest(".js_payment_debt_discount_block");
        var url                 = parent_block.data("url");//admin/registry/ajax_payment_debt_discount_form
        var payment_id          = parent_block.data("paymentId");

        $.post(url, {debt_discount_type: debt_discount_type, payment_id: payment_id}, function (res) {
            $modal.find(".modal-body").html(res.html);
            $modal.modal("show");
        }, "json");
    });

    $(document).on("keyup", ".js_debt_discount_field", function (e) {
        e.preventDefault();
        var $this = $(this);
        count_payment_gap($this, e);
    });

    $(document).on("click", ".js_payment_debt_discount_save", function (e) {
        e.preventDefault();
        var $this = $(this);
        count_payment_gap($this, e);
    });

    //$this - modal ichidagi biror button yoki input
    function count_payment_gap($this, $event) {

        var $modal          = $this.closest("#payment_debt_discount");
        var $form           = $modal.find("form");
        var payment_gap_dom = $modal.find(".js_payment_gap");
        var payment_gap     = $form.find("input[name='payment_gap']").val();
        var error_message_dom = $form.find(".js_payment_gap_error_message");

        error_message_dom.html("");
        var inputs = $form.find("input:not(:hidden)");
        var total_gap = 0;
        $.each(inputs, function (index, service_item) {
            if($(service_item).val() != '') {
                total_gap += parseInt($(service_item).val());
            }
        });

        let gap = payment_gap - total_gap;
        payment_gap_dom.html(gap);

        if($event.type == "click") {
            if(total_gap != payment_gap) {
                error_message_dom.html("Kiritilgan summalar yig'indisi kerak bo'lgan summadan ortiq yoki kam. <br>Iltimos tekshirib qayta kiriting!");
            } else {
                var url  = $form.prop("action");//admin/registry/ajax_payment_debt_discount_add
                var post = $form.serializeArray();
                $.post(url, post, function (res) {

                    $modal.modal("hide");
                }, "json")
            }
        }
    }


    $(".js_print_receipt_modal").click(function () {
        $("#print_receipt").modal("show");
    });


    $(".js_item_count").click(function () {
		let $this = $(this);
		let checkbox_block_dom 	= $this.closest(".checkbox");
		let checkbox_dom 		= checkbox_block_dom.find(".js_select_item");
		let modal_content 		= checkbox_dom.closest(".js_items_content");
		let item_count_value_input_dom = checkbox_block_dom.find(".js_item_count_value_input");
		let item_count_value_text_dom = $this.siblings(".js_item_count_value_text");
		var item_count = item_count_value_input_dom.val();

		if($this.hasClass("js_item_count_increase")) {
			item_count++;
			checkbox_dom.prop("checked", true);
			checkbox_block_dom.removeClass("bg-light").addClass("bg-info text-white");
		} else if(item_count > 1) {
			item_count--;
		} else {
			alert("Сони 1 дан кам бўлмаслиги керак!!!!");
		}

		item_count_value_input_dom.val(item_count)
		item_count_value_text_dom.html(item_count)

		/** ************************* **/


		var total = summarize_all_selected(modal_content);
		selected_items_menu_hide_show(checkbox_dom, modal_content);
		yigindi_block_hide_show(total, "items");
		bg_image_hide_show(total, modal_content);
		if(total == 0) {
			clear_payment_inputs();
		}
	});


	$(".js_reprint_cheque").click(function () {
			let $this = $(this);
			let url = $this.data("url");
			let payment_id = $this.data("paymentId");

			$.post(url, {payment_id: payment_id})
		});



    /** Eventlar tugadi **/
/** **********************************************************
 * Bemorlar uchun егпфвш
 * *********************************************************** */


});


