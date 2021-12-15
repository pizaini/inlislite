/*!
 * jQuery QueryBuilder 2.3.0
 * Locale: Indonesia (id)
 * Author: Henry , http://finasmart.com
 * Licensed under MIT (http://opensource.org/licenses/MIT)
 */

(function(root, factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery', 'query-builder'], factory);
    }
    else {
        factory(root.jQuery);
    }
}(this, function($) {
"use strict";

var QueryBuilder = $.fn.queryBuilder;

QueryBuilder.regional['id'] = {
  "__locale": "Indonesia (id)",
  "__author": "Henry, http://www.finasmart.com",
  "add_rule": "Tambah filter",
  "add_group": "Tambah group",
  "delete_rule": "Hapus",
  "delete_group": "Hapus",
  "conditions": {
    "AND": "Dan",
    "OR": "Atau"
  },
  "operators": {
    "contains": "Salah Satu Isi",
    "equal": "Tepat",
    "not_equal": "Tidak Tepat",
    "in": "Di Dalam",
    "not_in": "Di Luar",
    "less": "Kurang",
    "less_or_equal": "Kurang Atau Sama",
    "greater": "Lebih Besar",
    "greater_or_equal": "Lebih Besar Atau Sama",
    "between": "Diantara",
    "not_between": "Tidak Diantara",
    "begins_with": "Dimulai Dengan",
    "not_begins_with": "Tidak Dimulai Dengan",
    "not_contains": "Tidak mengandung",
    "ends_with": "Diakhiri Dengan",
    "not_ends_with": "Tidak Diakhiri Dengan",
    "is_empty": "Kosong",
    "is_not_empty": "Tidak Kosong",
    "is_null": "Null",
    "is_not_null": "Tidak Null"
  },
  "errors": {
    "no_filter": "Tidak ada filter yang dipilih",
    "empty_group": "Kelompok ini kosong",
    "radio_empty": "Tidak ada nilai yang dipilih",
    "checkbox_empty": "Tidak ada nilai yang dipilih",
    "select_empty": "Tidak ada nilai yang dipilih",
    "string_empty": "Nilai kosong",
    "string_exceed_min_length": "Harus mengandung setidaknya {0} karakter",
    "string_exceed_max_length": "Harus tidak mengandung lebih dari {0} karakter",
    "string_invalid_format": "Formatnya tidak ({0})",
    "number_nan": "Bukan angka",
    "number_not_integer": "Bukan integer",
    "number_not_double": "Tidak bilangan real",
    "number_exceed_min": "Harus lebih besar dari {0}",
    "number_exceed_max": "Harus lebih rendah dari {0}",
    "number_wrong_step": "Harus kelipatan dari {0}",
    "datetime_empty": "Nilai kosong",
    "datetime_invalid": "Format tanggal yang tidak valid ({0})",
    "datetime_exceed_min": "Harus setelah {0}",
    "datetime_exceed_max": "Harus sebelum {0}",
    "boolean_not_valid": "Tidak boolean",
    "operator_not_multiple": "Operator {0} tidak dapat menerima beberapa nilai"
  },
  "invert": "Membalikkan"
};

QueryBuilder.defaults({ lang_code: 'id' });
}));