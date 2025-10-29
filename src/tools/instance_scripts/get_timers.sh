#!/bin/sh

first_section=true
printf '{\n'

### Collect cron jobs
users=$(cut -f1 -d: /etc/passwd)
for u in $users; do
    crons=$(crontab -l -u "$u" 2>/dev/null | grep -v '^[[:space:]]*#' | grep -v '^[[:space:]]*$')
    if [ -n "$crons" ]; then
        if [ "$first_section" = true ]; then first_section=false; else printf ',\n'; fi
        printf '  "%s-cron": [' "$u"

        first_entry=true
        echo "$crons" | while IFS= read -r line; do
            pattern=$(printf '%s\n' "$line" | awk '{printf "%s %s %s %s %s", $1, $2, $3, $4, $5}')
            command=$(printf '%s\n' "$line" | awk '{for (i=6; i<=NF; i++) printf "%s%s", $i, (i<NF?" ":"")}')
            case "$command" in
                run-parts*)
                    dir=$(printf '%s\n' "$command" | awk '{print $2}')
                    if [ -d "$dir" ]; then
                        for f in "$dir"/*; do
                            [ -x "$f" ] || continue
                            esc_command=$(printf '%s' "$f" | sed 's/"/\\"/g')
                            esc_pattern=$(printf '%s' "$pattern" | sed 's/"/\\"/g')
                            if [ "$first_entry" = true ]; then first_entry=false; else printf ', '; fi
                            printf '{"pattern": "%s", "command": "%s"}' "$esc_pattern" "$esc_command"
                        done
                    fi
                    ;;
                *)
                    if [ -n "$command" ]; then
                        esc_command=$(printf '%s' "$command" | sed 's/"/\\"/g')
                        esc_pattern=$(printf '%s' "$pattern" | sed 's/"/\\"/g')
                        if [ "$first_entry" = true ]; then first_entry=false; else printf ', '; fi
                        printf '{"pattern": "%s", "command": "%s"}' "$esc_pattern" "$esc_command"
                    fi
                    ;;
            esac
        done
        printf ']'
    fi
done

### Collect systemd timers
timers=$(systemctl list-timers --all --no-legend --no-pager 2>/dev/null | awk '{print $(NF - 1)}' | sort -u)

for t in $timers; do
    # Extract proper schedule from the .timer file itself
    sched=$(systemctl cat "$t" 2>/dev/null | awk '/^\[Timer\]/ {found=1; next} /^\[/{found=0} found && /^On/ {print}' | head -n 1)
    [ -z "$sched" ] && sched="unknown"
    sched_value=$(printf '%s\n' "$sched" | cut -d= -f2- | xargs)

    # Determine linked service unit name
    service=$(echo "$t" | sed 's/\.timer$/.service/')

    # Get ExecStart from the unit definition (not runtime)
    exec_start_lines=$(systemctl cat "$service" 2>/dev/null | awk '/^\[Service\]/ {in_service=1; next} /^\[/{in_service=0} in_service && /^ExecStart=/' | cut -d= -f2-)

    [ -z "$exec_start_lines" ] && continue

    # Clean and format each command
    if [ "$first_section" = true ]; then first_section=false; else printf ',\n'; fi
    printf '  "systemd-%s": [' "$service"

    first_cmd=true
    echo "$exec_start_lines" | while IFS= read -r cmd; do
        # Strip anything after a space before a semicolon (i.e., multiple command format)
        cleaned=$(printf '%s\n' "$cmd" | sed -E 's/ *;.*$//' | xargs)
        [ -z "$cleaned" ] && continue

        esc_sched=$(printf '%s' "$sched_value" | sed 's/"/\\"/g')
        esc_cmd=$(printf '%s' "$cleaned" | sed 's/"/\\"/g')

        if [ "$first_cmd" = true ]; then first_cmd=false; else printf ', '; fi
        printf '{"pattern": "%s", "command": "%s"}' "$esc_sched" "$esc_cmd"
    done

    printf ']'
done

printf '\n}\n'
