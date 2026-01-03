# Dodolora (Dodotik Device) ERD Overview

## Quick Visual (Mermaid ER Diagram)

> Paste this block into any Mermaid-compatible viewer (e.g., GitHub, Mermaid Live Editor) to render the ERD.

```mermaid
erDiagram
    USERS ||--o{ DEVICES : "owns"
    DEVICES }o--|| DEVICE_TYPES : "belongs to"
    DEVICE_TYPES ||--o{ DEVICE_TYPE_DEVICE_PARAMETER : "template"
    DEVICE_PARAMETERS ||--o{ DEVICE_TYPE_DEVICE_PARAMETER : "assigned"
    DEVICE_TYPES ||--o{ DEVICE_TYPE_DEVICE_SETTING : "default"
    DEVICE_SETTINGS ||--o{ DEVICE_TYPE_DEVICE_SETTING : "applies"
    DEVICES ||--|| LIMIT_VALUES : "thresholds"
    DEVICES ||--|| DEVICE_SETTING_PER_DEVICES : "settings"
    DEVICES ||--o{ DEVICE_PARAMETERS_VALUES : "telemetry"
    DEVICE_PARAMETERS ||--o{ DEVICE_PARAMETERS_VALUES : "data"
    DEVICE_PARAMETERS ||--o{ PARAMETER_RANGE_COLORS : "ranges"
    DEVICES ||--o{ DEVICES_COMPONENTS : "widgets"
    COMPONENTS ||--o{ DEVICES_COMPONENTS : "configured"
    DEVICES ||--o{ DEVICE_FACTORIES : "attached"
    FACTORIES ||--o{ DEVICE_FACTORIES : "links"
    DEVICE_FACTORIES ||--o{ DEVICE_FACTORY_VALUES : "metrics"
    DEVICES ||--o{ DEVICE_FACTORY_VALUES : "metrics"
    FTP_FILES ||--o{ FILES_PARAMETERS_VALUES : "rows"
    GENERALS ||--o{ DOCUMENTATION : "consumed" %% conceptual consumption
    ABOUTS ||--o{ FRONT_PAGES : "content" %% conceptual consumption
```

*Note:* `DEVICE_TYPE_DEVICE_PARAMETER` and `DEVICE_TYPE_DEVICE_SETTING` represent pivot tables linking device types to parameters/settings; `DOCUMENTATION` and `FRONT_PAGES` denote conceptual usage of `generals` and `abouts` content.

## Core Entities and Relationships

1. **users**  
   *Key fields:* `id`, `username`, `email`, `password`, `role`  
   *Relationships:*  
   - 1⇢* `devices` via `user_id` (device ownership)  
   - Authorization gates rely on `role` (`isAdmin`, `isAdminOrUser`).

2. **generals**  
   *Key fields:* global branding/contact/meta fields, `logo`, `favicon`  
   *Notes:* Singleton settings used across views (front site, documentation, MQTT embeds).

3. **abouts**  
   *Key fields:* `title`, `subject`, `desc`  
   *Notes:* Static “About” page content.

4. **device_types**  
   *Key fields:* `id`, `name`, timestamps, soft deletes  
   *Relationships:*  
   - *⇢* `device_parameters` (pivot `device_type_device_parameter` with columns `order`, `length`, `rate`, `color`).  
   - *⇢* `device_settings` (pivot with `value`).  
   - 1⇢* `devices` (foreign key `type_id`).

5. **device_parameters**  
   *Key fields:* `id`, `name`, `code`, `unit`, soft deletes  
   *Relationships:*  
   - *⇢* `device_types` (above pivot).  
   - 1⇢* `parameter_range_colors` (threshold definitions).  
   - 1⇢* `device_parameters_values` (telemetry samples).

6. **parameter_range_colors**  
   *Key fields:* `id`, `parameter_id`, `from`, `to`, `color`  
   *Relationships:*  
   - Belongs to `device_parameters`.

7. **device_settings**  
   *Key fields:* `id`, `name`, etc., soft deletes  
   *Relationships:*  
   - *⇢* `device_types` (default values).  
   - 1⇢* `device_setting_per_devices` (per-device overrides).

8. **device_setting_per_devices**  
   *Key fields:* `id`, `device_id`, JSON `settings`  
   *Relationships:*  
   - Belongs to `devices`.

9. **devices**  
   *Key fields:* `id`, `device_id`, `user_id`, `type_id`, `tolerance`, `time_between_two_read`, `longitude`, `latitude`  
   *Relationships:*  
   - Belongs to `users` and `device_types`.  
   - 1⇢1 `limit_values`.  
   - 1⇢1 `device_setting_per_devices`.  
   - 1⇢* `device_parameters_values`.  
   - 1⇢* `device_components` / `devices_components` (dashboard widgets).  
   - 1⇢* `device_factories`.  
   - 1⇢* `device_factory_values`.

10. **limit_values**  
    *Key fields:* `device_id`, JSON `min_value`, `max_value`, `min_warning`, `max_warning`  
    *Relationships:*  
    - Belongs to `devices` (threshold alarms per parameter).

11. **device_parameters_values**  
    *Key fields:* `id`, `device_id`, JSON `parameters`, `time_of_read`  
    *Relationships:*  
    - Belongs to `devices` (raw telemetry snapshots).

12. **components / component_settings / devices_components / device_components**  
    *Purpose:* Define UI widgets and layout per device.  
    *Relationships:*  
    - `components` define reusable widgets.  
    - `component_settings` provide template options.  
    - `devices_components` links a device to components with per-widget settings/order.  
    - `device_components` legacy singular mapping (some controllers still reference it).

13. **factories**  
    *Key fields:* `id`, `name`  
    *Relationships:*  
    - 1⇢* `device_factories` (device attachments).  
    - 1⇢* `device_factory_values` (aggregated metrics).

14. **device_factories**  
    *Key fields:* `device_id`, `factory_id`, `start_date`, `is_attached`  
    *Relationships:*  
    - Belongs to `devices` and `factories`.  
    - 1⇢* `device_factory_values`.

15. **device_factory_values**  
    *Key fields:* `device_factory_id`, `device_id`, `parameter_code`, `value`, timestamps  
    *Relationships:*  
    - Belongs to `device_factories`; stores per-factory metrics.

16. **ftp_files**  
    *Key fields:* `id`, `name`, `extension`  
    *Relationships:*  
    - 1⇢* `files_parameters_values` (parsed CSV readings).

17. **files_parameters_values**  
    *Key fields:* `file_id`, JSON `parameters`, `time_of_read`  
    *Relationships:*  
    - Belongs to `ftp_files` (historical flow/TOT data).

18. **test_api / misc tables**  
    *Purpose:* store sample API payloads / developer diagnostics.

### Supporting Tables
- Standard Laravel tables (`password_reset_tokens`, `personal_access_tokens`, `failed_jobs`, etc.).  
- Sanctum (`personal_access_tokens`) and JWT configuration for API access.

---

## Relationship Summary (indented)
- `users`
  - hasMany `devices`
- `devices`
  - belongsTo `users`
  - belongsTo `device_types`
  - hasOne `limit_values`
  - hasOne `device_setting_per_devices`
  - hasMany `device_parameters_values`
  - hasMany `devices_components`
  - hasMany `device_factories`
  - hasMany `device_factory_values`
- `device_types`
  - hasMany `devices`
  - belongsToMany `device_parameters`
  - belongsToMany `device_settings`
- `device_parameters`
  - belongsToMany `device_types`
  - hasMany `parameter_range_colors`
  - hasMany `device_parameters_values`
- `device_settings`
  - belongsToMany `device_types`
  - hasMany `device_setting_per_devices`
- `device_factories`
  - belongsTo `devices`
  - belongsTo `factories`
  - hasMany `device_factory_values`
- `factories`
  - hasMany `device_factories`
  - hasMany `device_factory_values`
- `ftp_files`
  - hasMany `files_parameters_values`

Use this ERD outline with tooling like dbdiagram.io or Draw.io to generate a visual diagram.
