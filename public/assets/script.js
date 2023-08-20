document.addEventListener('DOMContentLoaded', function () {
    var elems = document.querySelectorAll('.dropdown-trigger');
    var instances = M.Dropdown.init(elems, []);
});

document.addEventListener('DOMContentLoaded', function () {
    var elems = document.querySelectorAll('.datepicker');
    var instances = M.Datepicker.init(elems, {
        showClearBtn: true,
        i18n: {
            cancel: 'Annuler',
            clear: 'Défaut',
            done: 'Valider',
            months: [
                'Janvier',
                'Février',
                'Mars',
                'Avril',
                'Mai',
                'Juin',
                'Juillet',
                'Août',
                'Septembre',
                'Octobre',
                'Novembre',
                'Décembre'
            ],
            monthsShort: [
                'Jan',
                'Fev',
                'Mars',
                'Avr',
                'Mai',
                'Juin',
                'Jul',
                'Août',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            ],
            weekdays: [
                'Dimanche',
                'Lundi',
                'Mardi',
                'Mercredi',
                'Jeudi',
                'Vendredi',
                'Samedi'
            ],
            weekdaysShort: [
                'Dim',
                'Lun',
                'Mar',
                'Mer',
                'Jeu',
                'Ven',
                'Sam'
            ],
            weekdaysAbbrev: ['D','L','M','M','J','V','S']
        }
    });
});