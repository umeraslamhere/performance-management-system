<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* includes/navbar.html.twig */
class __TwigTemplate_83b2ccf7316b63f44f9b35c6d2dc6fb1e7fdf6be6d038c231d9d5e0a77ba9665 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "includes/navbar.html.twig"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "includes/navbar.html.twig"));

        // line 1
        echo "<nav class=\"navbar navbar-expand-sm navbar-dark bg-primary mb-3\">
    <div class=\"container\">
        <a hr=\"/\" class=\"navbar-brand\">Logo</a>
        <button class=\"navbar-toggler\" type=\"button\" data-toggler=\"collapse\" data-target=\"#mobile\">
            <span class=\"navbar-toggler-icon\"></span>
        </button>

        <div class=\"collapse navbar-collapse\" id=\"mobile-nav\">
            <ul class=\"navbar-nav ml-auto \">
                ";
        // line 10
        if ($this->extensions['Symfony\Bridge\Twig\Extension\SecurityExtension']->isGranted("ROLE_USER")) {
            // line 11
            echo "                    <li>
                        <a href=\"/dashboard\" class=\"nav-link\">Home</a>
                    </li>
                    
                    ";
            // line 15
            if ($this->extensions['Symfony\Bridge\Twig\Extension\SecurityExtension']->isGranted("ROLE_ADMIN")) {
                // line 16
                echo "                        <li>
                            <a href=\"admin/signup_requests\" class=\"nav-link\">Signup Requests</a>
                        </li>

                    ";
            }
            // line 21
            echo "
                    ";
            // line 25
            echo "
                    <li class=\"float-right\">
                        <a href=\"";
            // line 27
            echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_logout");
            echo "\" class=\"nav-link\">Logout</a>
                    </li>

                ";
        } else {
            // line 30
            echo "    

                    <li class=\"float-right\">
                        <a href=\"";
            // line 33
            echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_login");
            echo "\" class=\"nav-link\">Login</a>
                    </li>

                ";
        }
        // line 37
        echo "
            </ul>
        </div>
    </div>

</nav>";
        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "includes/navbar.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  97 => 37,  90 => 33,  85 => 30,  78 => 27,  74 => 25,  71 => 21,  64 => 16,  62 => 15,  56 => 11,  54 => 10,  43 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<nav class=\"navbar navbar-expand-sm navbar-dark bg-primary mb-3\">
    <div class=\"container\">
        <a hr=\"/\" class=\"navbar-brand\">Logo</a>
        <button class=\"navbar-toggler\" type=\"button\" data-toggler=\"collapse\" data-target=\"#mobile\">
            <span class=\"navbar-toggler-icon\"></span>
        </button>

        <div class=\"collapse navbar-collapse\" id=\"mobile-nav\">
            <ul class=\"navbar-nav ml-auto \">
                {% if is_granted('ROLE_USER') %}
                    <li>
                        <a href=\"/dashboard\" class=\"nav-link\">Home</a>
                    </li>
                    
                    {% if is_granted('ROLE_ADMIN') %}
                        <li>
                            <a href=\"admin/signup_requests\" class=\"nav-link\">Signup Requests</a>
                        </li>

                    {% endif %}

                    {# <li>
                        <a href=\"/articles\" class=\"nav-link\">Articles</a>
                    </li> #}

                    <li class=\"float-right\">
                        <a href=\"{{ path('app_logout') }}\" class=\"nav-link\">Logout</a>
                    </li>

                {% else %}    

                    <li class=\"float-right\">
                        <a href=\"{{ path('app_login') }}\" class=\"nav-link\">Login</a>
                    </li>

                {% endif %}

            </ul>
        </div>
    </div>

</nav>", "includes/navbar.html.twig", "/home/coeus/Desktop/performance_management_product/templates/includes/navbar.html.twig");
    }
}
