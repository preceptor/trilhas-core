/**
 * Trilhas - Learning Management System
 * Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Adapted from Password Validator by Oliver Oechsle
 *  
 * @category   Application
 * @package    Application_Controller
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
(function($) { 
    var secondsInADay = 86400,
        passwordLifeTimeInDays = 365,
        passwordAttemptsPerSecond = 500,
        expressions = [{regex : /[A-Z]+/, uniqueChars : 26}, 
                       {regex : /[a-z]+/, uniqueChars : 26}, 
                       {regex : /[0-9]+/, uniqueChars : 10}, 
                       {regex : /[!\?.;,\\@$£#*()%~<>{}\[\]]+/, uniqueChars : 17}];
 
     function checkPassword(password) 
     { 
        var i,l = expressions.length, expression, 
            totalCombinations, crackTime, percentage,
            possibilitiesPerLetterInPassword = 0; 
 
        for (i = 0; i < l; i++) { 
            expression = expressions[i]; 
 
            if (expression.regex.exec(password)) { 
                possibilitiesPerLetterInPassword += expression.uniqueChars; 
            } 
        } 
 
        totalCombinations = Math.pow(possibilitiesPerLetterInPassword, password.length), 
        crackTime = ((totalCombinations / passwordAttemptsPerSecond) / 2) / secondsInADay, 
        percentage = crackTime / passwordLifeTimeInDays; 
 
        return Math.min(Math.max(password.length * 5, percentage * 100), 100); 
    }; 
 
    function updatePassword() 
    { 
        var  percentage  = checkPassword(this.val()), 
             progressBar = this.parent().find(".passwordStrengthBar div"); 
 
        progressBar.removeClass("strong medium weak useless") 
                   .stop() 
                   .animate({"width": percentage + "%"}); 
 
        if (percentage > 90) { 
            progressBar.addClass("strong"); 
        } else if (percentage > 50) { 
            progressBar.addClass("medium") 
        } else if (percentage > 10) { 
            progressBar.addClass("weak"); 
        } else { 
            progressBar.addClass("useless"); 
        } 
    } 
 
    $.fn.strength = function() { 
        this.bind('keyup', $.proxy(updatePassword, this)) 
            .after("<div class='passwordStrengthBar'>" + 
                   "<div></div>" + 
                   "</div>"); 
 
        updatePassword.apply(this); 
 
        return this;
    } 
})(jQuery); 