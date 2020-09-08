{foreach $docs as $type => $files}
    <strong>{$type}</strong>
    <ul>
    {foreach $files as $file}
        <li>
            <span class="docs-file">{$file.ext | upper}</span>
            <a href="{$file.url}" title="{$file.name}" target="_blank">{$file.name}</a>
        </li>
    {/foreach}
    </ul>
{/foreach}