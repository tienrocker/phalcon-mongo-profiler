<div class="page-header">
    <h1>Congratulations!</h1>
</div>

<p>You're now flying with Phalcon. Great things are about to happen!</p>

<em>This page is located at views/index/index.phtml</em>

{% if data is defined %}
    <table>
        {% for d in data %}
            <tr>
                <td>{{ d._id }}</td>
                <td>&nbsp; | &nbsp;</td>
                <td>{{ d.name }}</td>
            </tr>
        {% endfor %}
    </table>
{% endif %}